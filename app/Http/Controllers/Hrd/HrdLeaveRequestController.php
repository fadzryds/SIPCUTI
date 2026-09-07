<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HrdLeaveRequestController extends Controller
{
    /**
     * Display leave request monitoring.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::query()
            ->with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('request_number', 'like', "%{$search}%")

                    ->orWhereHas('employee', function ($employeeQuery) use ($search) {

                        $employeeQuery
                            ->where('nik', 'like', "%{$search}%")

                            ->orWhereHas('user', function ($userQuery) use ($search) {

                                $userQuery->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );

                            });

                    })

                    ->orWhereHas('leaveType', function ($leaveTypeQuery) use ($search) {

                        $leaveTypeQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LEAVE TYPE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('leave_type_id')) {

            $query->where(
                'leave_type_id',
                $request->leave_type_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $leaveRequests = $query
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalRequests = LeaveRequest::count();

        $pendingRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_PENDING
        )->count();

        $approvedRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_APPROVED
        )->count();

        $rejectedRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_REJECTED
        )->count();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.leave-requests.index',
            compact(
                'leaveRequests',
                'totalRequests',
                'pendingRequests',
                'approvedRequests',
                'rejectedRequests'
            )
        );
    }


    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leave)
    {
        $leave->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'employee.manager.user',
            'leaveType',
            'approvals.approver',
            'currentApprover',
        ]);

        return view(
            'hrd.leave-requests.show',
            compact('leave')
        );
    }


    /**
     * Download approved leave request PDF.
     *
     * HRD is only allowed to download the document.
     */
    public function downloadPdf(LeaveRequest $leave)
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
        | ONLY APPROVED REQUEST CAN BE DOWNLOADED
        |--------------------------------------------------------------------------
        */

        if ($leave->status !== LeaveRequest::STATUS_APPROVED) {

            return redirect()
                ->route(
                    'hrd.leave-requests.show',
                    $leave
                )
                ->with(
                    'error',
                    'Surat cuti hanya dapat diunduh setelah pengajuan disetujui.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        |
        | Menggunakan template PDF yang sudah kita buat sebelumnya:
        |
        | resources/views/pdf/leave-approval.blade.php
        |
        */

        $pdf = Pdf::loadView(
            'pdf.leave-approval',
            compact('leave')
        );


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            'Surat_Cuti_' . $leave->request_number . '.pdf'
        );
    }
}