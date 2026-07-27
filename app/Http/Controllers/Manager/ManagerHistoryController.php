<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerHistoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | History Approval
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $manager = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $query = LeaveRequest::with([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals',
        ])
        ->whereHas('employee', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        })
        ->whereHas('approvals', function ($q) {
            $q->where('approval_level', LeaveApproval::LEVEL_MANAGER)
              ->where('approver_id', Auth::id())
              ->whereIn('status', [
                  LeaveApproval::STATUS_APPROVED,
                  LeaveApproval::STATUS_REJECTED,
                  LeaveApproval::STATUS_PENDING,
              ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {

                $q->where('request_number', 'like', "%{$keyword}%")

                    ->orWhereHas('employee', function ($employee) use ($keyword) {

                        $employee->where('nik', 'like', "%{$keyword}%")

                            ->orWhereHas('user', function ($user) use ($keyword) {

                                $user->where('name', 'like', "%{$keyword}%");

                            });

                    });

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->whereHas('approvals', function ($q) use ($request) {

                $q->where('approval_level', LeaveApproval::LEVEL_MANAGER)
                    ->where('approver_id', Auth::id())
                    ->where('status', $request->status);

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Month Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('month')) {

            $query->whereMonth('submitted_at', $request->month);

        }

        /*
        |--------------------------------------------------------------------------
        | Year Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('year')) {

            $query->whereYear('submitted_at', $request->year);

        }

        $histories = $query
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $approved = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        $pending = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $total = LeaveApproval::where('approver_id', Auth::id())
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER)
            ->count();

        return view('manager.history.index', compact(
            'manager',
            'histories',
            'approved',
            'rejected',
            'pending',
            'total'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Detail History
    |--------------------------------------------------------------------------
    */

    public function show(LeaveRequest $leave)
    {
        $leave->load([

            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',

        ]);

        return view(
            'manager.history.show',
            compact('leave')
        );
    }
}