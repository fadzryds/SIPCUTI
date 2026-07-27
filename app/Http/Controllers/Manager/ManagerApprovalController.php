<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManagerApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Approval List
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $manager = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $leaveRequests = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
                'approvals',
            ])
            ->whereHas('employee', function ($query) use ($manager) {

                $query->where('manager_id', $manager->id);

            })
            ->latest()
            ->paginate(10);

        $pending = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_MANAGER
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $approved = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_MANAGER
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_MANAGER
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        return view(
            'manager.approval.index',
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
            'manager.approval.show',
            compact('leave')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Process Approval
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
        | Ambil Approval Manager
        |--------------------------------------------------------------------------
        */

        $approval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_MANAGER
            )
            ->where(
                'approver_id',
                Auth::id()
            )
            ->lockForUpdate()
            ->firstOrFail();

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
        | Update Approval Manager
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
        | Manager Approve
        |--------------------------------------------------------------------------
        */

        if ($request->action == LeaveApproval::STATUS_APPROVED) {

            $hrd = Employee::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )->first();

            LeaveApproval::where(
                    'leave_request_id',
                    $leave->id
                )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_HRD
                )
                ->update([

                    'status' => LeaveApproval::STATUS_PENDING,

                ]);

            $leave->update([

                'status' => LeaveRequest::STATUS_PENDING,

                'current_approver_id' => optional($hrd)->user_id,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Manager Reject
        |--------------------------------------------------------------------------
        */

        if ($request->action == LeaveApproval::STATUS_REJECTED) {

            LeaveApproval::where(
                    'leave_request_id',
                    $leave->id
                )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_HRD
                )
                ->update([

                    'status' => LeaveApproval::STATUS_WAITING,

                ]);

            $leave->update([

                'status' => LeaveRequest::STATUS_REJECTED,

                'current_approver_id' => null,

            ]);

        }

    });

    return redirect()
        ->route('manager.approval.index')
        ->with(
            'success',
            $request->action == LeaveApproval::STATUS_APPROVED
                ? 'Pengajuan cuti berhasil disetujui.'
                : 'Pengajuan cuti berhasil ditolak.'
        );
    }
}