<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HrdApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Approval List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
                'approvals',
            ])

            /*
            |--------------------------------------------------------------------------
            | Manager Sudah Approve
            |--------------------------------------------------------------------------
            */
            ->whereHas('approvals', function ($query) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_MANAGER
                    )
                    ->where(
                        'status',
                        LeaveApproval::STATUS_APPROVED
                    );

            })

            /*
            |--------------------------------------------------------------------------
            | Approval HRD
            |--------------------------------------------------------------------------
            */

            ->whereHas('approvals', function ($query) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_HRD
                    )
                    ->whereIn('status', [

                        LeaveApproval::STATUS_PENDING,
                        LeaveApproval::STATUS_APPROVED,
                        LeaveApproval::STATUS_REJECTED,

                    ]);

            });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $leaveRequests->where(function ($query) use ($search) {

                $query->where('request_number', 'like', "%{$search}%")

                    ->orWhereHas('employee.user', function ($q) use ($search) {

                        $q->where('name', 'like', "%{$search}%");

                    });

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $leaveRequests->whereHas('approvals', function ($query) use ($status) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_HRD
                    )
                    ->where(
                        'status',
                        $status
                    );

            });

        }

        $leaveRequests = $leaveRequests
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $approved = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        return view(
            'hrd.approval.index',
            compact(
                'leaveRequests',
                'pending',
                'approved',
                'rejected'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Approval
    |--------------------------------------------------------------------------
    */

    public function show(LeaveRequest $leave)
    {
        $leave->load([

            'employee.user',
            'employee.department',
            'employee.position',

            'leaveType',

            'approvals.approver',

        ]);

        return view(
            'hrd.approval.show',
            compact('leave')
        );
    } 

        /*
    |--------------------------------------------------------------------------
    | Process Approval HRD
    |--------------------------------------------------------------------------
    */

    public function process(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'action'    => ['required', 'in:Approved,Rejected'],
            'notes'     => ['nullable', 'string', 'max:1000'],
            'signature' => ['required', 'string'],
        ]);
    
        DB::transaction(function () use ($request, $leave) {
        
            /*
            |--------------------------------------------------------------------------
            | Ambil Approval HRD
            |--------------------------------------------------------------------------
            */
        
            $approval = LeaveApproval::where(
                    'leave_request_id',
                    $leave->id
                )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_HRD
                )
                ->lockForUpdate()
                ->firstOrFail();
                
            /*
            |--------------------------------------------------------------------------
            | Assign Approver Pertama Kali
            |--------------------------------------------------------------------------
            */
                
            if (empty($approval->approver_id)) {
            
                $approval->approver_id = Auth::id();
            
                $approval->save();
            
            }
        
            /*
            |--------------------------------------------------------------------------
            | Validasi Hak Approval
            |--------------------------------------------------------------------------
            */
        
            if ($approval->approver_id != Auth::id()) {
            
                abort(403, 'Anda tidak memiliki hak melakukan approval.');
            
            }
        
            /*
            |--------------------------------------------------------------------------
            | Cegah Approval Dua Kali
            |--------------------------------------------------------------------------
            */
        
            if ($approval->status !== LeaveApproval::STATUS_PENDING) {
            
                abort(403, 'Approval sudah diproses.');
            
            }
        
            /*
            |--------------------------------------------------------------------------
            | Simpan Signature
            |--------------------------------------------------------------------------
            */
        
            $signaturePath = null;
        
            if (
                preg_match(
                    '/^data:image\/(\w+);base64,/',
                    $request->signature
                )
            ) {
            
                $image = substr(
                    $request->signature,
                    strpos($request->signature, ',') + 1
                );
            
                $image = base64_decode($image);
            
                $filename = 'signature_' . Str::uuid() . '.png';
            
                $signaturePath = 'signatures/' . $filename;
            
                Storage::disk('public')->put(
                    $signaturePath,
                    $image
                );
            }
        
            /*
            |--------------------------------------------------------------------------
            | Update Approval HRD
            |--------------------------------------------------------------------------
            */
        
            $approval->update([
            
                'status'         => $request->action,
            
                'notes'          => $request->notes,
            
                'approved_at'    => now(),
            
                'signature_path' => $signaturePath,
            
            ]);
        
            /*
            |--------------------------------------------------------------------------
            | Final Status Leave Request
            |--------------------------------------------------------------------------
            */
        
            $leave->update([
            
                'status' => $request->action == LeaveApproval::STATUS_APPROVED
                    ? LeaveRequest::STATUS_APPROVED
                    : LeaveRequest::STATUS_REJECTED,
            
                'current_approver_id' => null,
            
            ]);
        
        });
    
        return redirect()
            ->route('hrd.approval.index')
            ->with(
                'success',
                $request->action == LeaveApproval::STATUS_APPROVED
                    ? 'Pengajuan cuti berhasil disetujui.'
                    : 'Pengajuan cuti berhasil ditolak.'
            );
    }
}