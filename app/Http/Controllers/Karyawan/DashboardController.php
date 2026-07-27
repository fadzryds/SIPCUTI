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
        | Statistik Dashboard
        |--------------------------------------------------------------------------
        */

        // Total hak cuti
        $totalLeave = LeaveBalance::where('employee_id', $employee->id)
            ->sum('remaining');

        // Sisa cuti
        $remainingLeave = LeaveBalance::where('employee_id', $employee->id)
            ->sum('remaining');

        // Total pengajuan
        $submitted = LeaveRequest::where('employee_id', $employee->id)
            ->count();

        // Pending
        $pending = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_PENDING)
            ->count();

        // Approved
        $approved = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->count();

        // Rejected
        $rejected = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_REJECTED)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Riwayat Pengajuan Terbaru
        |--------------------------------------------------------------------------
        */

        $leaveRequests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->latest()
            ->take(5)
            ->get();

        return view('karyawan.dashboard', compact(
            'employee',
            'totalLeave',
            'remainingLeave',
            'submitted',
            'pending',
            'approved',
            'rejected',
            'leaveRequests'
        ));
    }
}