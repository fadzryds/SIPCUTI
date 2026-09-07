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

class SupervisorApprovalController extends Controller
{
    public const LEVEL_SUPERVISOR = 'Supervisor';
    public const LEVEL_MANAGER = 'Manager';

    public const STATUS_PENDING = 'Pending';
    public const STATUS_WAITING = 'Waiting';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR
    |--------------------------------------------------------------------------
    */

    private function getSupervisor(): Employee
    {
        return Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $supervisor = $this->getSupervisor();

        $query = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ])
        ->whereHas('employee', function ($query) use ($supervisor) {
            $query->where(
                'supervisor_id',
                $supervisor->id
            );
        })
        ->whereHas('approvals', function ($query) use ($supervisor) {
            $query->where(
                'approver_id',
                $supervisor->user_id
            )
            ->where(
                'approval_level',
                self::LEVEL_SUPERVISOR
            )
            ->where(
                'status',
                self::STATUS_PENDING
            );
        });

        if ($request->filled('search')) {
            $query->where(
                'request_number',
                'like',
                '%' . $request->search . '%'
            );
        }

        $leaves = $query
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'supervisor.approval.index',
            compact('leaves')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(LeaveRequest $leave)
    {
        $supervisor = $this->getSupervisor();

        $approval = LeaveApproval::where(
            'leave_request_id',
            $leave->id
        )
        ->where(
            'approver_id',
            $supervisor->user_id
        )
        ->where(
            'approval_level',
            self::LEVEL_SUPERVISOR
        )
        ->firstOrFail();

        $leave->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ]);

        return view(
            'supervisor.approval.show',
            compact(
                'leave',
                'approval'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS
    |--------------------------------------------------------------------------
    */

    public function process(
        Request $request,
        LeaveRequest $leave
    ) {
        $request->validate([
            'action' => [
                'required',
                'in:approve,reject',
            ],

            'signature' => [
                'required_if:action,approve',
                'nullable',
                'string',
            ],

            'rejection_reason' => [
                'required_if:action,reject',
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $supervisor = $this->getSupervisor();

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | APPROVAL SUPERVISOR
            |--------------------------------------------------------------------------
            */

            $approval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
            ->where(
                'approver_id',
                $supervisor->user_id
            )
            ->where(
                'approval_level',
                self::LEVEL_SUPERVISOR
            )
            ->where(
                'status',
                self::STATUS_PENDING
            )
            ->lockForUpdate()
            ->first();

            if (!$approval) {
                throw new \Exception(
                    'Pengajuan cuti ini tidak sedang menunggu approval Anda.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | APPROVE
            |--------------------------------------------------------------------------
            */

            if ($request->action === 'approve') {

                $signaturePath =
                    $this->saveSignature(
                        $request->signature
                    );

                $approval->update([
                    'status' => self::STATUS_APPROVED,
                    'approved_at' => now(),
                    'signature_path' => $signaturePath,
                ]);

                /*
                |--------------------------------------------------------------
                | AKTIFKAN APPROVAL MANAGER
                |--------------------------------------------------------------
                */

                $managerApproval = LeaveApproval::where(
                    'leave_request_id',
                    $leave->id
                )
                ->where(
                    'approval_level',
                    self::LEVEL_MANAGER
                )
                ->first();

                if (!$managerApproval) {
                    throw new \Exception(
                        'Approval Manager tidak ditemukan.'
                    );
                }

                $managerApproval->update([
                    'status' => self::STATUS_PENDING,
                ]);

                /*
                |--------------------------------------------------------------
                | PINDAHKAN CURRENT APPROVER
                |--------------------------------------------------------------
                */

                $leave->update([
                    'current_approver_id' =>
                        $managerApproval->approver_id,
                ]);

                DB::commit();

                return redirect()
                    ->route('supervisor.approval.index')
                    ->with(
                        'success',
                        'Pengajuan cuti berhasil disetujui dan diteruskan ke Manager.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | REJECT
            |--------------------------------------------------------------------------
            */

            $approval->update([
                'status' => self::STATUS_REJECTED,
                'rejected_at' => now(),
                'rejection_reason' =>
                    $request->rejection_reason,
            ]);

            $leave->update([
                'status' => LeaveRequest::STATUS_REJECTED,
                'current_approver_id' => null,
            ]);

            DB::commit();

            return redirect()
                ->route('supervisor.approval.index')
                ->with(
                    'success',
                    'Pengajuan cuti berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
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
                'Format tanda tangan tidak valid.'
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
                'Tanda tangan tidak dapat diproses.'
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