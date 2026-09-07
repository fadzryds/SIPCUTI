<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupervisorLeaveController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET SUPERVISOR
    |--------------------------------------------------------------------------
    */

    private function getEmployee(): Employee
    {
        return Employee::with([
            'user',
            'department',
            'position',
        ])
            ->where(
                'user_id',
                Auth::id()
            )
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $employee = $this->getEmployee();

        $query = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ])
            ->whereHas(
                'approvals',
                function ($query) use ($employee) {

                    $query
                        ->where(
                            'approver_id',
                            $employee->user_id
                        )
                        ->where(
                            'approval_level',
                            LeaveApproval::LEVEL_SUPERVISOR
                        );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                function ($query) use ($search) {

                    $query
                        ->where(
                            'request_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas(
                            'employee.user',
                            function ($query) use ($search) {

                                $query->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $query->whereHas(
                'approvals',
                function ($query) use (
                    $employee,
                    $status
                ) {

                    $query
                        ->where(
                            'approver_id',
                            $employee->user_id
                        )
                        ->where(
                            'approval_level',
                            LeaveApproval::LEVEL_SUPERVISOR
                        )
                        ->where(
                            'status',
                            $status
                        );
                }
            );
        }

        $leaveRequests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
            'approver_id',
            $employee->user_id
        )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_SUPERVISOR
            )
            ->where(
                'status',
                LeaveApproval::STATUS_PENDING
            )
            ->count();

        $approved = LeaveApproval::where(
            'approver_id',
            $employee->user_id
        )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_SUPERVISOR
            )
            ->where(
                'status',
                LeaveApproval::STATUS_APPROVED
            )
            ->count();

        $rejected = LeaveApproval::where(
            'approver_id',
            $employee->user_id
        )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_SUPERVISOR
            )
            ->where(
                'status',
                LeaveApproval::STATUS_REJECTED
            )
            ->count();

        return view(
            'supervisor.leave.index',
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
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        LeaveRequest $leave
    ) {

        $employee = $this->getEmployee();

        $approval = LeaveApproval::where(
            'leave_request_id',
            $leave->id
        )
            ->where(
                'approver_id',
                $employee->user_id
            )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_SUPERVISOR
            )
            ->first();

        if (!$approval) {

            abort(
                403,
                'Anda tidak memiliki akses ke pengajuan ini.'
            );
        }

        $leave->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ]);

        return view(
            'supervisor.leave.show',
            compact(
                'leave',
                'approval'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        LeaveRequest $leave
    ) {

        $employee = $this->getEmployee();

        $request->validate([
            'signature' => [
                'required',
                'string',
            ],
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | FIND SUPERVISOR APPROVAL
            |--------------------------------------------------------------------------
            */

            $approval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
                ->where(
                    'approver_id',
                    $employee->user_id
                )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_SUPERVISOR
                )
                ->where(
                    'status',
                    LeaveApproval::STATUS_PENDING
                )
                ->lockForUpdate()
                ->first();

            if (!$approval) {

                throw new \Exception(
                    'Pengajuan ini tidak sedang menunggu persetujuan Anda.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE SUPERVISOR SIGNATURE
            |--------------------------------------------------------------------------
            */

            $supervisorSignature =
                $this->saveSignature(
                    $request->signature
                );

            /*
            |--------------------------------------------------------------------------
            | APPROVE
            |--------------------------------------------------------------------------
            */

            $approval->update([

                'status' =>
                    LeaveApproval::STATUS_APPROVED,

                'approved_at' =>
                    now(),

                'signature_path' =>
                    $supervisorSignature,
            ]);

            /*
            |--------------------------------------------------------------------------
            | FIND MANAGER APPROVAL
            |--------------------------------------------------------------------------
            */

            $managerApproval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_MANAGER
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | MOVE TO MANAGER
            |--------------------------------------------------------------------------
            */

            if ($managerApproval) {

                $managerApproval->update([
                    'status' =>
                        LeaveApproval::STATUS_PENDING,
                ]);

                $leave->update([

                    'current_approver_id' =>
                        $managerApproval->approver_id,

                    'status' =>
                        LeaveRequest::STATUS_PENDING,
                ]);
            }

            DB::commit();

            return redirect()
                ->route(
                    'supervisor.leave.index'
                )
                ->with(
                    'success',
                    'Pengajuan cuti berhasil disetujui. Tanda tangan Supervisor berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        LeaveRequest $leave
    ) {

        $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $employee = $this->getEmployee();

        DB::beginTransaction();

        try {

            $approval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
                ->where(
                    'approver_id',
                    $employee->user_id
                )
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_SUPERVISOR
                )
                ->where(
                    'status',
                    LeaveApproval::STATUS_PENDING
                )
                ->lockForUpdate()
                ->first();

            if (!$approval) {

                throw new \Exception(
                    'Pengajuan ini tidak sedang menunggu persetujuan Anda.'
                );
            }

            $approval->update([

                'status' =>
                    LeaveApproval::STATUS_REJECTED,

                'rejection_reason' =>
                    $request->rejection_reason,
            ]);

            $leave->update([

                'status' =>
                    LeaveRequest::STATUS_REJECTED,

                'current_approver_id' =>
                    null,
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'supervisor.leave.index'
                )
                ->with(
                    'success',
                    'Pengajuan cuti berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE SIGNATURE
    |--------------------------------------------------------------------------
    */

    private function saveSignature(
        string $signature
    ): string {

        if (
            !preg_match(
                '/^data:image\/(\w+);base64,/',
                $signature
            )
        ) {

            throw new \Exception(
                'Format tanda tangan Supervisor tidak valid.'
            );
        }

        $image = substr(
            $signature,
            strpos($signature, ',') + 1
        );

        $image = base64_decode(
            $image,
            true
        );

        if ($image === false) {

            throw new \Exception(
                'Tanda tangan Supervisor tidak dapat diproses.'
            );
        }

        $filename =
            'signature_' .
            Str::uuid() .
            '.png';

        $path =
            'signatures/' .
            $filename;

        Storage::disk('public')->put(
            $path,
            $image
        );

        return $path;
    }
}