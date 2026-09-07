<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class DirectorDashboardController extends Controller
{
    /**
     * Dashboard Director
     */
    public function index()
    {
        $director = Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | APPROVAL DIRECTOR
        |--------------------------------------------------------------------------
        */

        $approvalQuery = LeaveApproval::where(
            'approval_level',
            LeaveApproval::LEVEL_DIRECTOR
        )->where(
            'approver_id',
            Auth::id()
        );

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $pending = (clone $approvalQuery)
            ->where(
                'status',
                LeaveApproval::STATUS_PENDING
            )
            ->count();

        $approved = (clone $approvalQuery)
            ->where(
                'status',
                LeaveApproval::STATUS_APPROVED
            )
            ->count();

        $rejected = (clone $approvalQuery)
            ->where(
                'status',
                LeaveApproval::STATUS_REJECTED
            )
            ->count();

        $total = $pending + $approved + $rejected;


        /*
        |--------------------------------------------------------------------------
        | TOTAL PENGAJUAN CUTI
        |--------------------------------------------------------------------------
        */

        $totalLeave = LeaveRequest::whereHas(
            'approvals',
            function ($query) {
                $query
                    ->where(
                        'approval_level',
                        LeaveApproval::LEVEL_DIRECTOR
                    )
                    ->where(
                        'approver_id',
                        Auth::id()
                    );
            }
        )->count();


        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $recentLeaves = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ])
        ->whereHas(
            'approvals',
            function ($query) {

                $query
                    ->where(
                        'approval_level',
                        LeaveApproval::LEVEL_DIRECTOR
                    )
                    ->where(
                        'approver_id',
                        Auth::id()
                    );
            }
        )
        ->latest()
        ->limit(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN YANG MENUNGGU
        |--------------------------------------------------------------------------
        */

        $pendingLeaves = LeaveRequest::with([
            'employee.user',
            'leaveType',
        ])
        ->whereHas(
            'approvals',
            function ($query) {

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
                        LeaveApproval::STATUS_PENDING
                    );
            }
        )
        ->latest()
        ->limit(5)
        ->get();


        return view(
            'director.dashboard',
            compact(
                'director',
                'pending',
                'approved',
                'rejected',
                'total',
                'totalLeave',
                'recentLeaves',
                'pendingLeaves'
            )
        );
    }
}