<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $query = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id);

        if ($request->filled('search')) {
            $query->where('request_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leaveRequests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $remainingLeave = LeaveBalance::where('employee_id', $employee->id)
            ->sum('remaining');

        $pending = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'Pending')
            ->count();

        $approved = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->count();

        $rejected = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'Rejected')
            ->count();

        return view('karyawan.leave.index', compact(
            'leaveRequests',
            'remainingLeave',
            'pending',
            'approved',
            'rejected'
        ));
    }

    public function create()
    {
        $employee = Employee::with([
            'user',
            'department',
            'position'
        ])->where('user_id', Auth::id())->firstOrFail();

        $leaveTypes = LeaveType::where('is_active', 1)->get();

        $remainingLeave = LeaveBalance::where('employee_id', $employee->id)
            ->sum('remaining');

        $manager = Employee::with('user')
            ->where('approval_level', 'Manager')
            ->first();

        $hrd = Employee::with('user')
            ->where('approval_level', 'HRD')
            ->first();

        return view('karyawan.leave.create', compact(
            'employee',
            'leaveTypes',
            'remainingLeave',
            'manager',
            'hrd'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string|max:1000',
            'attachment'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $employee = Employee::where('user_id', Auth::id())
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Cari Manager & HRD
            |--------------------------------------------------------------------------
            */

            $manager = Employee::where('approval_level', 'Manager')->firstOrFail();

            $hrd = Employee::where('approval_level', 'HRD')->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Hitung Lama Cuti
            |--------------------------------------------------------------------------
            */

            $totalDays = Carbon::parse($request->start_date)
                ->diffInDays(Carbon::parse($request->end_date)) + 1;

            /*
            |--------------------------------------------------------------------------
            | Upload Lampiran
            |--------------------------------------------------------------------------
            */

            $attachment = null;

            if ($request->hasFile('attachment')) {

                $attachment = $request->file('attachment')
                    ->store('leave-attachments', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan Leave Request
            |--------------------------------------------------------------------------
            */

            $leave = LeaveRequest::create([

                'request_number' => 'LV-' . now()->format('YmdHis'),

                'employee_id' => $employee->id,

                'leave_type_id' => $request->leave_type_id,

                'start_date' => $request->start_date,

                'end_date' => $request->end_date,

                'total_days' => $totalDays,

                'reason' => $request->reason,

                'attachment' => $attachment,

                'status' => LeaveRequest::STATUS_PENDING,

                'submitted_at' => now(),

                // gunakan USER ID Manager
                'current_approver_id' => $manager->user_id,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Approval Manager
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([

                'leave_request_id' => $leave->id,

                'approver_id' => $manager->user_id,

                'approval_level' => LeaveApproval::LEVEL_MANAGER,

                'status' => LeaveApproval::STATUS_PENDING,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Approval HRD
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([

                'leave_request_id' => $leave->id,

                'approver_id' => $hrd->user_id,

                'approval_level' => LeaveApproval::LEVEL_HRD,

                'status' => LeaveApproval::STATUS_WAITING,

            ]);

            DB::commit();

            return redirect()
                ->route('employee.leave.index')
                ->with('success', 'Pengajuan cuti berhasil dikirim.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(LeaveRequest $leave)
    {
        //
    }

    public function edit(LeaveRequest $leave)
    {
        //
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        //
    }

    public function destroy(LeaveRequest $leave)
    {
        if ($leave->status != 'Pending') {

            return back()->with(
                'error',
                'Pengajuan yang sudah diproses tidak dapat dihapus.'
            );
        }

        if ($leave->attachment) {

            Storage::disk('public')->delete($leave->attachment);
        }

        $leave->delete();

        return back()->with(
            'success',
            'Pengajuan cuti berhasil dihapus.'
        );
    }

    /*
|--------------------------------------------------------------------------
| Download PDF Surat Cuti
|--------------------------------------------------------------------------
*/

public function downloadPdf(LeaveRequest $leave)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan hanya pemilik yang dapat mengunduh
        |--------------------------------------------------------------------------
        */
    
        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();
    
        if ($leave->employee_id != $employee->id) {
        
            abort(403, 'Anda tidak memiliki akses.');
        
        }
    
        /*
        |--------------------------------------------------------------------------
        | PDF hanya dapat diunduh jika sudah selesai diproses
        |--------------------------------------------------------------------------
        */
    
        if ($leave->status !== LeaveRequest::STATUS_APPROVED) {
        
            return back()->with(
                'error',
                'PDF hanya dapat diunduh setelah pengajuan disetujui HRD.'
            );
        
        }
    
        /*
        |--------------------------------------------------------------------------
        | Load Seluruh Relasi
        |--------------------------------------------------------------------------
        */
    
        $leave->load([
        
            'employee.user',
            'employee.department',
            'employee.position',
        
            'leaveType',
        
            'approvals.approver',
        
        ]);
    
        /*
        |--------------------------------------------------------------------------
        | Approval Manager & HRD
        |--------------------------------------------------------------------------
        */
    
        $managerApproval = $leave->approvals
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->first();
    
        $hrdApproval = $leave->approvals
            ->where('approval_level', LeaveApproval::LEVEL_HRD)
            ->first();
    
        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */
    
        $pdf = Pdf::loadView(
            'pdf.leave-approval',
            [
            
                'leave' => $leave,
            
                'managerApproval' => $managerApproval,
            
                'hrdApproval' => $hrdApproval,
            
            ]
        );
    
        $pdf->setPaper('A4', 'portrait');
    
        return $pdf->download(
            'Surat_Cuti_'.$leave->request_number.'.pdf'
        );
    }
}