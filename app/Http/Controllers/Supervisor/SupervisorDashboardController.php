<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class SupervisorDashboardController extends Controller
{
    public function index()
    {
        $employee = Employee::where(
            'user_id',
            Auth::id()
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN MENUNGGU APPROVAL
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'approval_level',
            'Supervisor'
        )
        ->where(
            'status',
            'Pending'
        )
        ->count();

        /*
        |--------------------------------------------------------------------------
        | DISETUJUI
        |--------------------------------------------------------------------------
        */

        $approved = LeaveApproval::where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'approval_level',
            'Supervisor'
        )
        ->where(
            'status',
            'Approved'
        )
        ->count();

        /*
        |--------------------------------------------------------------------------
        | DITOLAK
        |--------------------------------------------------------------------------
        */

        $rejected = LeaveApproval::where(
            'approver_id',
            Auth::id()
        )
        ->where(
            'approval_level',
            'Supervisor'
        )
        ->where(
            'status',
            'Rejected'
        )
        ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $total = $pending + $approved + $rejected;

        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $recentLeaves = LeaveRequest::with([
            'employee.user',
            'leaveType',
        ])
        ->whereHas('approvals', function ($query) {
            $query
                ->where(
                    'approver_id',
                    Auth::id()
                )
                ->where(
                    'approval_level',
                    'Supervisor'
                );
        })
        ->latest()
        ->take(5)
        ->get();

        return view(
            'supervisor.dashboard',
            compact(
                'employee',
                'pending',
                'approved',
                'rejected',
                'total',
                'recentLeaves'
            )
        );
    }
}