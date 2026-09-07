<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DirectorApprovalController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ])
        ->whereHas('approvals', function ($query) {

            $query
                ->where(
                    'approval_level',
                    LeaveApproval::LEVEL_DIRECTOR
                )
                ->where(
                    'approver_id',
                    Auth::id()
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
                    function ($query) use ($search) {

                        $query->where(
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
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $query->whereHas('approvals', function ($query) use ($status) {

                $query
                    ->where(
                        'approval_level',
                        LeaveApproval::LEVEL_DIRECTOR
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
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
            'approval_level',
            LeaveApproval::LEVEL_DIRECTOR
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
            LeaveApproval::LEVEL_DIRECTOR
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
            LeaveApproval::LEVEL_DIRECTOR
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
            'director.approval.index',
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

    public function show(LeaveRequest $leave)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD DATA
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
        | SECURITY
        |--------------------------------------------------------------------------
        |
        | Director hanya boleh melihat approval yang memang
        | ditujukan kepada dirinya.
        |
        */

        $approval = LeaveApproval::where(
            'leave_request_id',
            $leave->id
        )
        ->where(
            'approval_level',
            LeaveApproval::LEVEL_DIRECTOR
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
            'director.approval.show',
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
            | AMBIL APPROVAL DIRECTOR
            |--------------------------------------------------------------------------
            */

            $approval = LeaveApproval::where(
                'leave_request_id',
                $leave->id
            )
            ->where(
                'approval_level',
                LeaveApproval::LEVEL_DIRECTOR
            )
            ->where(
                'approver_id',
                Auth::id()
            )
            ->lockForUpdate()
            ->first();


            /*
            |--------------------------------------------------------------------------
            | SECURITY
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
                    'Approval Director untuk pengajuan ini sudah diproses.'
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
            | UPDATE APPROVAL DIRECTOR
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
            | DIRECTOR APPROVE
            |--------------------------------------------------------------------------
            */

            if (
                $request->action ===
                LeaveApproval::STATUS_APPROVED
            ) {

                /*
                |--------------------------------------------------------------------------
                | FINAL APPROVED
                |--------------------------------------------------------------------------
                */

                $leave->update([
                    'status' =>
                        LeaveRequest::STATUS_APPROVED,

                    'current_approver_id' =>
                        null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | DIRECTOR REJECT
            |--------------------------------------------------------------------------
            */

            if (
                $request->action ===
                LeaveApproval::STATUS_REJECTED
            ) {

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
            ->route('director.approval.index')
            ->with(
                'success',

                $request->action ===
                LeaveApproval::STATUS_APPROVED

                    ? 'Pengajuan cuti berhasil disetujui. Proses approval telah selesai.'

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