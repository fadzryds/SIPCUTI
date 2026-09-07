<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Position;
use App\Models\LeaveBalance;

class HrdDashboardController extends Controller
{
    /**
     * Display HRD dashboard.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CURRENT YEAR
        |--------------------------------------------------------------------------
        */

        $currentYear = now()->year;

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalEmployees = Employee::count();

        $activeEmployees = Employee::where(
            'status',
            'active'
        )->count();

        $inactiveEmployees = Employee::where(
            'status',
            '!=',
            'active'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        $totalDepartments = Department::count();

        $totalPositions = Position::count();

        /*
        |--------------------------------------------------------------------------
        | LEAVE REQUEST STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalLeaveRequests = LeaveRequest::count();

        $pendingLeaveRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_PENDING
        )->count();

        $approvedLeaveRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_APPROVED
        )->count();

        $rejectedLeaveRequests = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_REJECTED
        )->count();

        /*
        |--------------------------------------------------------------------------
        | LEAVE DAYS STATISTICS
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | approvedLeaveDays menggunakan total_days,
        | bukan count().
        |
        */

        $approvedLeaveDays = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_APPROVED
        )->sum('total_days');

        $pendingLeaveDays = LeaveRequest::where(
            'status',
            LeaveRequest::STATUS_PENDING
        )->sum('total_days');

        /*
        |--------------------------------------------------------------------------
        | CURRENT YEAR LEAVE BALANCE
        |--------------------------------------------------------------------------
        */

        $totalLeaveQuota = LeaveBalance::query()
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('quota');

        $totalUsedLeave = LeaveBalance::query()
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('used');

        $totalRemainingLeave = LeaveBalance::query()
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('remaining');

        /*
        |--------------------------------------------------------------------------
        | RECENT LEAVE REQUESTS
        |--------------------------------------------------------------------------
        */

        $recentLeaveRequests = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'leaveType',
        ])
            ->latest()
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECENT EMPLOYEES
        |--------------------------------------------------------------------------
        */

        $recentEmployees = Employee::with([
            'user',
            'department',
            'position',
        ])
            ->latest('id')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.dashboard',
            compact(
                'currentYear',

                'totalEmployees',
                'activeEmployees',
                'inactiveEmployees',

                'totalDepartments',
                'totalPositions',

                'totalLeaveRequests',
                'pendingLeaveRequests',
                'approvedLeaveRequests',
                'rejectedLeaveRequests',

                'approvedLeaveDays',
                'pendingLeaveDays',

                'totalLeaveQuota',
                'totalUsedLeave',
                'totalRemainingLeave',

                'recentLeaveRequests',
                'recentEmployees'
            )
        );
    }
}