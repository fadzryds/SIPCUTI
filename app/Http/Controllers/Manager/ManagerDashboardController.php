<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $manager = Employee::with('user')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $pending = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $approved = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Total Bawahan
        |--------------------------------------------------------------------------
        */

        $totalSubordinates = Employee::where(
            'manager_id',
            $manager->id
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Pengajuan Terbaru
        |--------------------------------------------------------------------------
        */

        $leaveRequests = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
                'managerApproval',
            ])
            ->whereHas('employee', function ($query) use ($manager) {
                $query->where('manager_id', $manager->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact(
            'manager',
            'pending',
            'approved',
            'rejected',
            'totalSubordinates',
            'leaveRequests'
        ));
    }
}