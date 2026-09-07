<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Position;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Exports\LeaveReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HrdLeaveReportController extends Controller
{
    /**
     * Display leave report.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER DATA
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $leaveTypes = LeaveType::query()
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $query = $this->buildQuery($request);


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalRequests = (clone $query)->count();

        $totalDays = (clone $query)->sum('total_days');

        $pendingRequests = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_PENDING
            )
            ->count();

        $approvedRequests = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_APPROVED
            )
            ->count();

        $rejectedRequests = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_REJECTED
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL DAYS BY STATUS
        |--------------------------------------------------------------------------
        */

        $approvedDays = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_APPROVED
            )
            ->sum('total_days');

        $pendingDays = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_PENDING
            )
            ->sum('total_days');

        $rejectedDays = (clone $query)
            ->where(
                'status',
                LeaveRequest::STATUS_REJECTED
            )
            ->sum('total_days');


        /*
        |--------------------------------------------------------------------------
        | RECAP PER EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employeeRecap = (clone $query)
            ->with([
                'employee.user',
                'employee.department',
                'employee.position',
            ])
            ->get()
            ->groupBy('employee_id')
            ->map(function ($items) {

                $employee = $items->first()->employee;

                return (object) [

                    'employee_id' => $employee?->id,

                    'name' =>
                        $employee?->user?->name
                        ?? 'Tidak diketahui',

                    'nik' =>
                        $employee?->nik
                        ?? '-',

                    'department' =>
                        $employee?->department?->name
                        ?? '-',

                    'position' =>
                        $employee?->position?->name
                        ?? '-',

                    'request_count' =>
                        $items->count(),

                    'total_days' =>
                        $items->sum('total_days'),

                    'approved_days' =>
                        $items
                            ->where(
                                'status',
                                LeaveRequest::STATUS_APPROVED
                            )
                            ->sum('total_days'),

                ];

            })
            ->sortByDesc('total_days')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECAP PER DEPARTMENT
        |--------------------------------------------------------------------------
        */

        $departmentRecap = (clone $query)
            ->with([
                'employee.department',
            ])
            ->get()
            ->groupBy(function ($leave) {

                return $leave->employee?->department_id
                    ?? 0;

            })
            ->map(function ($items) {

                $department =
                    $items->first()->employee?->department;

                /*
                |--------------------------------------------------------------
                | Hitung jumlah karyawan unik
                |--------------------------------------------------------------
                */

                $employeeCount = $items
                    ->pluck('employee_id')
                    ->filter()
                    ->unique()
                    ->count();

                return (object) [

                    'department_id' =>
                        $department?->id,

                    'name' =>
                        $department?->name
                        ?? 'Tanpa Department',

                    'request_count' =>
                        $items->count(),

                    'employee_count' =>
                        $employeeCount,

                ];

            })
            ->sortByDesc('employee_count')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RECAP PER LEAVE TYPE
        |--------------------------------------------------------------------------
        */

        $leaveTypeRecap = (clone $query)
            ->with([
                'leaveType',
            ])
            ->get()
            ->groupBy('leave_type_id')
            ->map(function ($items) {

                $leaveType =
                    $items->first()->leaveType;

                return (object) [

                    'name' =>
                        $leaveType?->name
                        ?? 'Jenis Cuti Tidak Diketahui',

                    'request_count' =>
                        $items->count(),

                    'total_days' =>
                        $items->sum('total_days'),

                    'approved_days' =>
                        $items
                            ->where(
                                'status',
                                LeaveRequest::STATUS_APPROVED
                            )
                            ->sum('total_days'),

                ];

            })
            ->sortByDesc('total_days')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PAGINATED DETAIL
        |--------------------------------------------------------------------------
        */

        $leaveRequests = $query
            ->with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
            ])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.reports.leave',
            compact(
                'departments',
                'positions',
                'leaveTypes',
                'leaveRequests',
                'employeeRecap',
                'departmentRecap',
                'leaveTypeRecap',
                'totalRequests',
                'totalDays',
                'pendingRequests',
                'approvedRequests',
                'rejectedRequests',
                'approvedDays',
                'pendingDays',
                'rejectedDays'
            )
        );
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
            'department_id',
            'position_id',
            'leave_type_id',
            'status',
        ]);
    
        $fileName =
            'laporan-cuti-' .
            now()->format('Y-m-d-His') .
            '.xlsx';
    
        return Excel::download(
            new LeaveReportExport($filters),
            $fileName
        );
    }

    /**
     * Build filtered leave request query.
     */
    private function buildQuery(Request $request): Builder
    {
        $query = LeaveRequest::query();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->input('search')
            );

            $query->where(function (Builder $q) use ($search) {

                $q->where(
                    'request_number',
                    'like',
                    "%{$search}%"
                );

                $q->orWhereHas(
                    'employee',
                    function (Builder $employeeQuery) use ($search) {

                        $employeeQuery
                            ->where(
                                'nik',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'user',
                                function (Builder $userQuery) use ($search) {

                                    $userQuery->where(
                                        'name',
                                        'like',
                                        "%{$search}%"
                                    );

                                }
                            );

                    }
                );

                $q->orWhereHas(
                    'leaveType',
                    function (Builder $leaveTypeQuery) use ($search) {

                        $leaveTypeQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | DATE FROM
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'start_date',
                '>=',
                $request->input('date_from')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE TO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'end_date',
                '<=',
                $request->input('date_to')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {

            $query->whereHas(
                'employee',
                function (Builder $employeeQuery) use ($request) {

                    $employeeQuery->where(
                        'department_id',
                        $request->input('department_id')
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | POSITION
        |--------------------------------------------------------------------------
        */

        if ($request->filled('position_id')) {

            $query->whereHas(
                'employee',
                function (Builder $employeeQuery) use ($request) {

                    $employeeQuery->where(
                        'position_id',
                        $request->input('position_id')
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LEAVE TYPE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('leave_type_id')) {

            $query->where(
                'leave_type_id',
                $request->input('leave_type_id')
            );
        }


        return $query;
    }
}