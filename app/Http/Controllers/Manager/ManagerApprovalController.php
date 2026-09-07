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
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Manager hanya boleh melihat approval yang memang ditujukan kepada
    | dirinya sendiri.
    |
    */

    public function index(Request $request)
    {
        $manager = Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        $query = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ])

        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        |
        | Hanya approval Manager milik user yang sedang login.
        |
        */

        ->whereHas('approvals', function ($query) {

            $query->where(
                'approval_level',
                LeaveApproval::LEVEL_MANAGER
            )
            ->where(
                'approver_id',
                Auth::id()
            );

        })

        /*
        |--------------------------------------------------------------------------
        | SECURITY TAMBAHAN
        |--------------------------------------------------------------------------
        |
        | Pastikan employee memang berada di bawah manager ini.
        |
        */

        ->whereHas('employee', function ($query) use ($manager) {

            $query->where(
                'manager_id',
                $manager->id
            );

        });

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($query) use ($search) {

                $query->where(
                    'request_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'employee.user',
                    function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $query->whereHas('approvals', function ($query) use ($status) {

                $query->where(
                    'approval_level',
                    LeaveApproval::LEVEL_MANAGER
                )
                ->where(
                    'approver_id',
                    Auth::id()
                )
                ->where(
                    'status',
                    $status
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $leaveRequests = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
            'approval_level',
            LeaveApproval::LEVEL_MANAGER
        )
        ->where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'status',
            LeaveApproval::STATUS_PENDING
        )
        ->count();

        $approved = LeaveApproval::where(
            'approval_level',
            LeaveApproval::LEVEL_MANAGER
        )
        ->where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'status',
            LeaveApproval::STATUS_APPROVED
        )
        ->count();

        $rejected = LeaveApproval::where(
            'approval_level',
            LeaveApproval::LEVEL_MANAGER
        )
        ->where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'status',
            LeaveApproval::STATUS_REJECTED
        )
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
    | SHOW
    |--------------------------------------------------------------------------
    |
    | Manager hanya bisa membuka pengajuan yang memang:
    |
    | 1. Milik bawahannya
    | 2. Memiliki approval Manager
    | 3. Approval tersebut ditujukan kepada dirinya
    |
    */

    public function show(LeaveRequest $leave)
    {
        $manager = Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    
        /*
        |--------------------------------------------------------------------------
        | LOAD EMPLOYEE
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
        | SECURITY 1
        |--------------------------------------------------------------------------
        |
        | Pastikan employee memang bawahan manager yang sedang login.
        |
        */
    
        if (
            (int) $leave->employee->manager_id !==
            (int) $manager->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke pengajuan ini.'
            );
        }
    
        /*
        |--------------------------------------------------------------------------
        | SECURITY 2
        |--------------------------------------------------------------------------
        |
        | Pastikan pengajuan mempunyai approval Manager
        | yang ditujukan kepada user manager yang sedang login.
        |
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
            ->first();
    
        if (!$approval) {
            abort(
                403,
                'Pengajuan ini bukan tanggung jawab Anda.'
            );
        }
    
        return view(
            'manager.approval.show',
            compact('leave')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESS APPROVAL
    |--------------------------------------------------------------------------
    */

    public function process(
    Request $request,
    LeaveRequest $leave
) {
    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'action' => [
            'required',
            'in:Approved,Rejected',
        ],

        'notes' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'signature' => [
            'required',
            'string',
        ],
    ]);


    DB::transaction(function () use (
        $request,
        $leave
    ) {

        /*
        |--------------------------------------------------------------------------
        | MANAGER LOGIN
        |--------------------------------------------------------------------------
        */

        $manager = Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | LOAD EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $leave->loadMissing([
            'employee',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AMBIL APPROVAL MANAGER
        |--------------------------------------------------------------------------
        |
        | Approval menjadi sumber utama pengecekan hak akses.
        |
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
        ->first();


        /*
        |--------------------------------------------------------------------------
        | APPROVAL TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!$approval) {

            abort(
                403,
                'Pengajuan ini bukan tanggung jawab Anda.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CEGAH APPROVAL ULANG
        |--------------------------------------------------------------------------
        */

        if (
            $approval->status !==
            LeaveApproval::STATUS_PENDING
        ) {

            abort(
                403,
                'Approval Manager untuk pengajuan ini sudah diproses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SECURITY TAMBAHAN
        |--------------------------------------------------------------------------
        |
        | Pastikan karyawan memang bawahan manager yang login.
        |
        */

        if (
            (int) $leave->employee->manager_id !==
            (int) $manager->id
        ) {

            abort(
                403,
                'Karyawan ini bukan bawahan Manager yang sedang login.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN SIGNATURE
        |--------------------------------------------------------------------------
        */

        $signaturePath = $this->saveSignature(
            $request->signature
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE APPROVAL MANAGER
        |--------------------------------------------------------------------------
        */

        $approval->update([
            'status' => $request->action,
            'notes' => $request->notes,
            'approved_at' => now(),
            'signature_path' => $signaturePath,
        ]);


        /*
        |--------------------------------------------------------------------------
        | MANAGER APPROVE
        |--------------------------------------------------------------------------
        */

        if (
            $request->action ===
            LeaveApproval::STATUS_APPROVED
        ) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL APPROVAL DIRECTOR
            |--------------------------------------------------------------------------
            */

            $directorApproval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_DIRECTOR
            )
            ->lockForUpdate()
            ->first();


            if (!$directorApproval) {

                throw new \Exception(
                    'Approval Director untuk pengajuan ini tidak ditemukan.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | AKTIFKAN DIRECTOR
            |--------------------------------------------------------------------------
            */

            $directorApproval->update([
                'status' =>
                    LeaveApproval::STATUS_PENDING,
            ]);


            /*
            |--------------------------------------------------------------------------
            | PINDAHKAN CURRENT APPROVER
            |--------------------------------------------------------------------------
            */

            $leave->update([
                'status' =>
                    LeaveRequest::STATUS_PENDING,

                'current_approver_id' =>
                    $directorApproval->approver_id,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER REJECT
        |--------------------------------------------------------------------------
        */

        if (
            $request->action ===
            LeaveApproval::STATUS_REJECTED
        ) {

            /*
            |--------------------------------------------------------------------------
            | NONAKTIFKAN DIRECTOR
            |--------------------------------------------------------------------------
            */

            LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_DIRECTOR
            )
            ->update([
                'status' =>
                    LeaveApproval::STATUS_WAITING,
            ]);


            /*
            |--------------------------------------------------------------------------
            | FINAL REJECTED
            |--------------------------------------------------------------------------
            */

            $leave->update([
                'status' =>
                    LeaveRequest::STATUS_REJECTED,

                'current_approver_id' =>
                    null,
            ]);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('manager.approval.index')
        ->with(
            'success',
            $request->action === LeaveApproval::STATUS_APPROVED

                ? 'Pengajuan cuti berhasil disetujui dan diteruskan ke Director.'

                : 'Pengajuan cuti berhasil ditolak.'
        );
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
                'Tanda tangan tidak valid.'
            );
        }


        $path =
            'signatures/signature_' .
            Str::uuid() .
            '.png';


        Storage::disk('public')->put(
            $path,
            $image
        );


        return $path;
    }
}