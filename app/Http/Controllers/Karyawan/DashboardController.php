<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee = Employee::with([
            'user',
            'department',
            'position',
            'manager.user',
        ])
        ->where('user_id', Auth::id())
        ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CURRENT YEAR
        |--------------------------------------------------------------------------
        */

        $currentYear = now()->year;

        $totalLeave = LeaveBalance::query()
            ->where('employee_id', $employee->id)
            ->where('year', $currentYear)
            ->where('is_active', true)
            ->sum('quota');

        $usedLeave = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $remainingLeave = max(
            $totalLeave - $usedLeave,
            0
        );


        /*
        |--------------------------------------------------------------------------
        | TOTAL PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $submitted = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->whereYear('start_date', $currentYear)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        */

        $pending = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_PENDING)
            ->whereYear('start_date', $currentYear)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | APPROVED
        |--------------------------------------------------------------------------
        */

        $approved = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->whereYear('start_date', $currentYear)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | REJECTED
        |--------------------------------------------------------------------------
        */

        $rejected = LeaveRequest::query()
            ->where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_REJECTED)
            ->whereYear('start_date', $currentYear)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENGAJUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $leaveRequests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'karyawan.dashboard',
            compact(
                'employee',
                'totalLeave',
                'remainingLeave',
                'usedLeave',
                'submitted',
                'pending',
                'approved',
                'rejected',
                'leaveRequests'
            )
        );
    }
}