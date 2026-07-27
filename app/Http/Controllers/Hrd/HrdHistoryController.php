<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class HrdHistoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | History List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $leaveRequests = LeaveRequest::with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
                'approvals.approver',
            ])

            /*
            |--------------------------------------------------------------------------
            | Hanya yang sudah selesai diproses HRD
            |--------------------------------------------------------------------------
            */

            ->whereHas('approvals', function ($query) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_HRD
                    )
                    ->whereIn('status', [

                        LeaveApproval::STATUS_APPROVED,
                        LeaveApproval::STATUS_REJECTED,

                    ]);

            });

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $leaveRequests->where(function ($query) use ($search) {

                $query->where(
                        'request_number',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas('employee.user', function ($q) use ($search) {

                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    });

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $status = $request->status;

            $leaveRequests->whereHas('approvals', function ($query) use ($status) {

                $query->where(
                        'approval_level',
                        LeaveApproval::LEVEL_HRD
                    )
                    ->where(
                        'status',
                        $status
                    );

            });

        }

        $leaveRequests = $leaveRequests
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $approved = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where(
                'status',
                LeaveApproval::STATUS_APPROVED
            )
            ->count();

        $rejected = LeaveApproval::where(
                'approval_level',
                LeaveApproval::LEVEL_HRD
            )
            ->where(
                'status',
                LeaveApproval::STATUS_REJECTED
            )
            ->count();

        return view(
            'hrd.history.index',
            compact(
                'leaveRequests',
                'approved',
                'rejected'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | History Detail
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
            'hrd.history.show',
            compact('leave')
        );
    }
}