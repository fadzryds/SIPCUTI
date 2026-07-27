<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class HrdDashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | HRD Login
        |--------------------------------------------------------------------------
        */

        $hrd = Employee::with([
                'user',
                'department',
                'position',
            ])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Summary
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $approved = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Employee
        |--------------------------------------------------------------------------
        */

        $totalEmployees = Employee::count();

        /*
        |--------------------------------------------------------------------------
        | Leave Today
        |--------------------------------------------------------------------------
        */

        $leaveToday = LeaveRequest::whereDate(
                'start_date',
                today()
            )
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Recent Request
        |--------------------------------------------------------------------------
        | Hanya yang sudah di-approve Manager
        | dan sedang menunggu HRD
        |--------------------------------------------------------------------------
        */

        $recentApprovals = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
                'approvals',
            ])
            ->whereHas('approvals', function ($query) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_MANAGER
                    )
                    ->where(
                        'status',
                        LeaveApproval::STATUS_APPROVED
                    );

            })
            ->whereHas('approvals', function ($query) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_HRD
                    )
                    ->whereIn('status', [
                        LeaveApproval::STATUS_PENDING,
                        LeaveApproval::STATUS_APPROVED,
                        LeaveApproval::STATUS_REJECTED,
                    ]);

            })
            ->latest()
            ->take(5)
            ->get();

        return view(
            'hrd.dashboard',
            compact(
                'hrd',
                'pending',
                'approved',
                'rejected',
                'totalEmployees',
                'leaveToday',
                'recentApprovals'
            )
        );
    }
}