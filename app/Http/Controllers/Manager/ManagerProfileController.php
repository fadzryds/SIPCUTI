<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use Illuminate\Support\Facades\Auth;

class ManagerProfileController extends Controller
{
    public function index()
    {
        $manager = Employee::with([
            'user',
            'department',
            'position',
        ])
        ->where('user_id', Auth::id())
        ->firstOrFail();

        $totalApproval = LeaveApproval::where(
                'approver_id',
                Auth::id()
            )->count();

        $approved = LeaveApproval::where(
                'approver_id',
                Auth::id()
            )
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where(
                'approver_id',
                Auth::id()
            )
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        $pending = LeaveApproval::where(
                'approver_id',
                Auth::id()
            )
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        return view(
            'manager.manager-profile',
            compact(
                'manager',
                'totalApproval',
                'approved',
                'rejected',
                'pending'
            )
        );
    }
}