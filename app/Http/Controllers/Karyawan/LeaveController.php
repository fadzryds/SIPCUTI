<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | APPROVAL LEVEL
    |--------------------------------------------------------------------------
    */

    public const LEVEL_EMPLOYEE   = 'Employee';
    public const LEVEL_SUPERVISOR = 'Supervisor';
    public const LEVEL_MANAGER    = 'Manager';
    public const LEVEL_DIRECTOR   = 'Director';

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_WAITING  = 'Waiting';
    public const STATUS_PENDING  = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /*
    |--------------------------------------------------------------------------
    | GET CURRENT EMPLOYEE
    |--------------------------------------------------------------------------
    */

    private function getEmployee(): Employee
    {
        return Employee::with([
            'user',
            'department',
            'position',
        ])
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | GET OWNED LEAVE
    |--------------------------------------------------------------------------
    */

    private function getOwnedLeave(
        LeaveRequest $leave
    ): LeaveRequest {
        $employee = $this->getEmployee();

        if (
            (int) $leave->employee_id !==
            (int) $employee->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke pengajuan cuti ini.'
            );
        }

        return $leave;
    }

    /*
    |--------------------------------------------------------------------------
    | GET HOLIDAY DATES
    |--------------------------------------------------------------------------
    */

    private function getHolidayDates(): array
    {
        return collect(
            config('leave.holidays', [])
        )
            ->map(
                fn ($date) =>
                Carbon::parse($date)->format('Y-m-d')
            )
            ->unique()
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK WORKING DAY
    |--------------------------------------------------------------------------
    */

    private function isWorkingDay(Carbon $date): bool
    {
        /*
        |--------------------------------------------------------------------------
        | SABTU / MINGGU
        |--------------------------------------------------------------------------
        */

        if ($date->isWeekend()) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | HARI LIBUR
        |--------------------------------------------------------------------------
        */

        return !in_array(
            $date->format('Y-m-d'),
            $this->getHolidayDates(),
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE WORKING DAYS
    |--------------------------------------------------------------------------
    |
    | Sabtu       = tidak dihitung
    | Minggu      = tidak dihitung
    | Hari libur  = tidak dihitung
    |
    */

    private function calculateDays(
        string $startDate,
        string $endDate
    ): int {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lt($start)) {
            throw new \Exception(
                'Tanggal selesai tidak boleh sebelum tanggal mulai.'
            );
        }

        $totalDays = 0;

        $period = CarbonPeriod::create(
            $start,
            $end
        );

        foreach ($period as $date) {
            if ($this->isWorkingDay($date)) {
                $totalDays++;
            }
        }

        return $totalDays;
    }

    /*
    |--------------------------------------------------------------------------
    | GET LEAVE BALANCE
    |--------------------------------------------------------------------------
    |
    | Mengambil balance berdasarkan:
    |
    | employee
    | leave type
    | tahun
    | aktif
    |
    */

    private function getLeaveBalance(
        Employee $employee,
        LeaveType $leaveType,
        ?int $year = null
    ): ?LeaveBalance {
        $year ??= now()->year;

        return LeaveBalance::query()
            ->where(
                'employee_id',
                $employee->id
            )
            ->where(
                'leave_type_id',
                $leaveType->id
            )
            ->where(
                'year',
                $year
            )
            ->where(
                'is_active',
                true
            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | GET AVAILABLE LEAVE DAYS
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | remaining - pending request lain
    |
    | Request yang sedang diedit dikecualikan.
    |
    */

    private function getAvailableLeaveDays(
        Employee $employee,
        LeaveType $leaveType,
        ?int $excludeLeaveId = null
    ): int {
        $balance = $this->getLeaveBalance(
            $employee,
            $leaveType,
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA BALANCE
        |--------------------------------------------------------------------------
        */

        if (!$balance) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | REMAINING
        |--------------------------------------------------------------------------
        |
        | remaining adalah saldo aktual yang tersedia
        | dari leave_balances.
        |
        */

        $remaining = max(
            (int) $balance->remaining,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | PENDING REQUEST
        |--------------------------------------------------------------------------
        */

        $pendingQuery = LeaveRequest::query()
            ->where(
                'employee_id',
                $employee->id
            )
            ->where(
                'leave_type_id',
                $leaveType->id
            )
            ->where(
                'status',
                LeaveRequest::STATUS_PENDING
            );

        /*
        |--------------------------------------------------------------------------
        | EXCLUDE CURRENT REQUEST
        |--------------------------------------------------------------------------
        */

        if ($excludeLeaveId !== null) {
            $pendingQuery->where(
                'id',
                '!=',
                $excludeLeaveId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL PENDING
        |--------------------------------------------------------------------------
        */

        $pendingDays = (int) $pendingQuery
            ->sum('total_days');

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE
        |--------------------------------------------------------------------------
        */

        return max(
            $remaining - $pendingDays,
            0
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET APPROVAL HIERARCHY
    |--------------------------------------------------------------------------
    */

    private function getApprovalHierarchy(
        Employee $employee
    ): array {
        $supervisor = null;
        $manager = null;
        $director = null;

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        if ($employee->supervisor_id) {
            $supervisor = Employee::with('user')
                ->find(
                    $employee->supervisor_id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        if (
            $supervisor &&
            $supervisor->manager_id
        ) {
            $manager = Employee::with('user')
                ->find(
                    $supervisor->manager_id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DIRECTOR
        |--------------------------------------------------------------------------
        */

        if (
            $manager &&
            $manager->director_id
        ) {
            $director = Employee::with('user')
                ->find(
                    $manager->director_id
                );
        }

        return [
            'supervisor' => $supervisor,
            'manager' => $manager,
            'director' => $director,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
{
    $employee = $this->getEmployee();

    /*
    |--------------------------------------------------------------------------
    | LEAVE REQUESTS
    |--------------------------------------------------------------------------
    */

    $query = LeaveRequest::with([
        'leaveType',
        'approvals.approver',
    ])
        ->where(
            'employee_id',
            $employee->id
        );

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {
        $query->where(
            'request_number',
            'like',
            '%' . $request->search . '%'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {
        $query->where(
            'status',
            $request->status
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    $leaveRequests = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | CURRENT YEAR
    |--------------------------------------------------------------------------
    */

    $currentYear = now()->year;

    /*
    |--------------------------------------------------------------------------
    | TOTAL HAK CUTI
    |--------------------------------------------------------------------------
    |
    | Total hak cuti diambil dari QUOTA pada leave_balances.
    |
    | Contoh:
    |
    | Cuti Tahunan = 12 hari
    | Cuti Khusus  =  3 hari
    |
    | Total hak cuti = 15 hari
    |
    */

    $totalLeave = LeaveBalance::query()
        ->where(
            'employee_id',
            $employee->id
        )
        ->where(
            'year',
            $currentYear
        )
        ->where(
            'is_active',
            true
        )
        ->sum('quota');

    /*
    |--------------------------------------------------------------------------
    | CUTI YANG SUDAH DIGUNAKAN
    |--------------------------------------------------------------------------
    |
    | Yang dihitung hanya pengajuan yang sudah APPROVED.
    |
    | total_days berasal dari jumlah hari kerja:
    | - Senin-Jumat dihitung
    | - Sabtu tidak dihitung
    | - Minggu tidak dihitung
    | - Hari libur tidak dihitung
    |
    | Contoh:
    |
    | Hak cuti = 12 hari
    | Cuti #1   = 5 hari APPROVED
    |
    | usedLeave = 5
    |
    */

    $usedLeave = LeaveRequest::query()
        ->where(
            'employee_id',
            $employee->id
        )
        ->where(
            'status',
            LeaveRequest::STATUS_APPROVED
        )
        ->whereYear(
            'start_date',
            $currentYear
        )
        ->sum('total_days');

    /*
    |--------------------------------------------------------------------------
    | SISA CUTI
    |--------------------------------------------------------------------------
    |
    | Sisa cuti dihitung dari:
    |
    | total hak cuti - total hari cuti yang sudah APPROVED
    |
    | Contoh:
    |
    | 12 - 5 = 7 hari
    |
    */

    $remainingLeave = max(
        (int) $totalLeave - (int) $usedLeave,
        0
    );

    /*
    |--------------------------------------------------------------------------
    | STATISTICS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | PENDING
    |--------------------------------------------------------------------------
    */

    $pending = LeaveRequest::query()
        ->where(
            'employee_id',
            $employee->id
        )
        ->where(
            'status',
            LeaveRequest::STATUS_PENDING
        )
        ->count();

    /*
    |--------------------------------------------------------------------------
    | APPROVED
    |--------------------------------------------------------------------------
    */

    $approved = LeaveRequest::query()
        ->where(
            'employee_id',
            $employee->id
        )
        ->where(
            'status',
            LeaveRequest::STATUS_APPROVED
        )
        ->count();

    /*
    |--------------------------------------------------------------------------
    | REJECTED
    |--------------------------------------------------------------------------
    */

    $rejected = LeaveRequest::query()
        ->where(
            'employee_id',
            $employee->id
        )
        ->where(
            'status',
            LeaveRequest::STATUS_REJECTED
        )
        ->count();

    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'karyawan.leave.index',
        compact(
            'leaveRequests',
            'totalLeave',
            'usedLeave',
            'remainingLeave',
            'pending',
            'approved',
            'rejected'
        )
    );
}

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        public function create()
        {
            $employee = $this->getEmployee();

            /*
            |--------------------------------------------------------------------------
            | CURRENT YEAR
            |--------------------------------------------------------------------------
            */

            $currentYear = now()->year;

            /*
            |--------------------------------------------------------------------------
            | LEAVE TYPES
            |--------------------------------------------------------------------------
            */

            $leaveTypes = LeaveType::query()
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('name')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE DAYS PER TYPE
            |--------------------------------------------------------------------------
            */

            $availableLeaveDays = [];

            foreach ($leaveTypes as $type) {

                $availableLeaveDays[$type->id] =
                    $this->getAvailableLeaveDays(
                        $employee,
                        $type
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | TOTAL QUOTA
            |--------------------------------------------------------------------------
            */

            $totalLeave = LeaveBalance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'year',
                    $currentYear
                )
                ->where(
                    'is_active',
                    true
                )
                ->sum('quota');

            /*
            |--------------------------------------------------------------------------
            | USED
            |--------------------------------------------------------------------------
            */

            $usedLeave = LeaveBalance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'year',
                    $currentYear
                )
                ->where(
                    'is_active',
                    true
                )
                ->sum('used');

            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            $remainingLeave = LeaveBalance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
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
            | PENDING DAYS
            |--------------------------------------------------------------------------
            |
            | Hari yang sedang diajukan dan belum selesai approval.
            |
            */

            $pendingDays = LeaveRequest::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'status',
                    LeaveRequest::STATUS_PENDING
                )
                ->sum('total_days');

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE TOTAL DAYS
            |--------------------------------------------------------------------------
            |
            | Hari yang benar-benar masih dapat digunakan untuk pengajuan.
            |
            */

            $availableTotalDays = max(
                (int) $remainingLeave - (int) $pendingDays,
                0
            );

            if ($availableTotalDays <= 0) {

                return redirect()
                    ->route('employee.leave.index')
                    ->with(
                        'error',
                        'Anda tidak dapat mengajukan cuti karena seluruh hak cuti tahun ' .
                        $currentYear .
                        ' sudah habis atau sedang digunakan dalam pengajuan yang masih menunggu approval.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | APPROVAL HIERARCHY
            |--------------------------------------------------------------------------
            */

            $hierarchy = $this->getApprovalHierarchy(
                $employee
            );

            $supervisor = $hierarchy['supervisor'];
            $manager = $hierarchy['manager'];
            $director = $hierarchy['director'];

            return view(
                'karyawan.leave.create',
                compact(
                    'employee',
                    'leaveTypes',
                    'totalLeave',
                    'usedLeave',
                    'remainingLeave',
                    'pendingDays',
                    'availableTotalDays',
                    'availableLeaveDays',
                    'supervisor',
                    'manager',
                    'director'
                )
            );
        }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'leave_type_id' => [
                'required',
                'integer',
                'exists:leave_types,id',
            ],

            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],

            'signature' => [
                'required',
                'string',
                'max:5000000',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        DB::beginTransaction();

        $attachment = null;
        $employeeSignature = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE
            |--------------------------------------------------------------------------
            */

            $employee = $this->getEmployee();

            /*
            |--------------------------------------------------------------------------
            | APPROVAL HIERARCHY
            |--------------------------------------------------------------------------
            */

            $hierarchy = $this->getApprovalHierarchy(
                $employee
            );

            $supervisor = $hierarchy['supervisor'];
            $manager = $hierarchy['manager'];
            $director = $hierarchy['director'];

            /*
            |--------------------------------------------------------------------------
            | VALIDATE APPROVAL
            |--------------------------------------------------------------------------
            */

            if (!$supervisor) {
                throw new \Exception(
                    'Supervisor untuk karyawan ini belum ditentukan.'
                );
            }

            if (!$manager) {
                throw new \Exception(
                    'Manager untuk supervisor ini belum ditentukan.'
                );
            }

            if (!$director) {
                throw new \Exception(
                    'Director untuk manager ini belum ditentukan.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | LEAVE TYPE
            |--------------------------------------------------------------------------
            */

            $leaveType = LeaveType::query()
                ->where(
                    'id',
                    $validated['leave_type_id']
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();

            if (!$leaveType) {
                throw new \Exception(
                    'Jenis cuti tidak ditemukan atau tidak aktif.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CALCULATE WORKING DAYS
            |--------------------------------------------------------------------------
            */

            $totalDays = $this->calculateDays(
                $validated['start_date'],
                $validated['end_date']
            );

            /*
            |--------------------------------------------------------------------------
            | VALIDATE WORKING DAYS
            |--------------------------------------------------------------------------
            */

            if ($totalDays <= 0) {
                throw new \Exception(
                    'Rentang tanggal yang dipilih tidak memiliki hari kerja. ' .
                    'Sabtu, Minggu, dan hari libur tidak dihitung sebagai hari cuti.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDATE MAX DAYS PER REQUEST
            |--------------------------------------------------------------------------
            |
            | LeaveType.max_days digunakan sebagai batas maksimal
            | satu pengajuan.
            |
            */

            if (
                $leaveType->max_days !== null &&
                (int) $leaveType->max_days > 0 &&
                $totalDays > (int) $leaveType->max_days
            ) {
                throw new \Exception(
                    'Pengajuan cuti "' .
                    $leaveType->name .
                    '" maksimal ' .
                    $leaveType->max_days .
                    ' hari kerja per pengajuan. ' .
                    'Pengajuan Anda membutuhkan ' .
                    $totalDays .
                    ' hari kerja.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CURRENT YEAR
            |--------------------------------------------------------------------------
            */

            $currentYear = now()->year;

            /*
            |--------------------------------------------------------------------------
            | GET BALANCE
            |--------------------------------------------------------------------------
            */

            $balance = LeaveBalance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where(
                    'year',
                    $currentYear
                )
                ->where(
                    'is_active',
                    true
                )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | BALANCE WAJIB ADA
            |--------------------------------------------------------------------------
            */

            if (!$balance) {
                throw new \Exception(
                    'Saldo cuti untuk jenis "' .
                    $leaveType->name .
                    '" tidak ditemukan pada tahun ' .
                    $currentYear .
                    '.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | QUOTA
            |--------------------------------------------------------------------------
            */

            $quota = max(
                (int) $balance->quota,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | USED
            |--------------------------------------------------------------------------
            */

            $used = max(
                (int) $balance->used,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            $remaining = max(
                (int) $balance->remaining,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | PENDING REQUEST
            |--------------------------------------------------------------------------
            */
            
            $pendingDays = LeaveRequest::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where(
                    'status',
                    LeaveRequest::STATUS_PENDING
                )
                ->sum('total_days');
            
            /*
            |--------------------------------------------------------------------------
            | AVAILABLE DAYS
            |--------------------------------------------------------------------------
            */
            
            $availableDays = max(
                $remaining - (int) $pendingDays,
                0
            );
            
            /*
            |--------------------------------------------------------------------------
            | CHECK REMAINING
            |--------------------------------------------------------------------------
            */
            
            if ($remaining <= 0) {
            
                throw new \Exception(
                    'Hak cuti untuk jenis "' .
                    $leaveType->name .
                    '" sudah habis. ' .
                    'Quota: ' .
                    $quota .
                    ' hari, ' .
                    'sudah digunakan: ' .
                    $used .
                    ' hari, ' .
                    'sisa: 0 hari.'
                );
            }
            
            /*
            |--------------------------------------------------------------------------
            | CHECK PENDING
            |--------------------------------------------------------------------------
            */
            
            if ($availableDays <= 0) {
            
                throw new \Exception(
                    'Hak cuti untuk jenis "' .
                    $leaveType->name .
                    '" tidak dapat digunakan untuk pengajuan baru karena seluruh ' .
                    'sisa cuti sedang digunakan dalam pengajuan yang masih menunggu approval. ' .
                    'Sisa: ' .
                    $remaining .
                    ' hari, ' .
                    'sedang diajukan: ' .
                    (int) $pendingDays .
                    ' hari.'
                );
            }
            
            /*
            |--------------------------------------------------------------------------
            | CHECK QUOTA
            |--------------------------------------------------------------------------
            */
            
            if ($totalDays > $availableDays) {
            
                throw new \Exception(
                    'Saldo cuti tidak mencukupi. ' .
                    'Quota: ' .
                    $quota .
                    ' hari, ' .
                    'sudah digunakan: ' .
                    $used .
                    ' hari, ' .
                    'sisa: ' .
                    $remaining .
                    ' hari, ' .
                    'sedang diajukan: ' .
                    (int) $pendingDays .
                    ' hari, ' .
                    'tersedia untuk pengajuan: ' .
                    $availableDays .
                    ' hari. ' .
                    'Pengajuan membutuhkan: ' .
                    $totalDays .
                    ' hari kerja.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ATTACHMENT
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('attachment')) {
                $attachment = $request
                    ->file('attachment')
                    ->store(
                        'leave-attachments',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | REQUEST NUMBER
            |--------------------------------------------------------------------------
            */

            $requestNumber =
                'LV-' .
                now()->format('YmdHis') .
                '-' .
                Str::upper(
                    Str::random(4)
                );

            /*
            |--------------------------------------------------------------------------
            | SAVE SIGNATURE
            |--------------------------------------------------------------------------
            */

            $employeeSignature = $this->saveSignature(
                $validated['signature']
            );

            /*
            |--------------------------------------------------------------------------
            | CREATE LEAVE REQUEST
            |--------------------------------------------------------------------------
            */

            $leave = LeaveRequest::create([
                'request_number' =>
                    $requestNumber,

                'employee_id' =>
                    $employee->id,

                'leave_type_id' =>
                    $leaveType->id,

                'start_date' =>
                    $validated['start_date'],

                'end_date' =>
                    $validated['end_date'],

                'total_days' =>
                    $totalDays,

                'reason' =>
                    $validated['reason'],

                'attachment' =>
                    $attachment,

                'employee_signature_path' =>
                    $employeeSignature,

                'status' =>
                    LeaveRequest::STATUS_PENDING,

                'submitted_at' =>
                    now(),

                'current_approver_id' =>
                    $supervisor->user_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE APPROVAL
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([
                'leave_request_id' =>
                    $leave->id,

                'approver_id' =>
                    $employee->user_id,

                'approval_level' =>
                    self::LEVEL_EMPLOYEE,

                'status' =>
                    self::STATUS_APPROVED,

                'approved_at' =>
                    now(),

                'signature_path' =>
                    $employeeSignature,
            ]);

            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR APPROVAL
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([
                'leave_request_id' =>
                    $leave->id,

                'approver_id' =>
                    $supervisor->user_id,

                'approval_level' =>
                    self::LEVEL_SUPERVISOR,

                'status' =>
                    self::STATUS_PENDING,
            ]);

            /*
            |--------------------------------------------------------------------------
            | MANAGER APPROVAL
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([
                'leave_request_id' =>
                    $leave->id,

                'approver_id' =>
                    $manager->user_id,

                'approval_level' =>
                    self::LEVEL_MANAGER,

                'status' =>
                    self::STATUS_WAITING,
            ]);

            /*
            |--------------------------------------------------------------------------
            | DIRECTOR APPROVAL
            |--------------------------------------------------------------------------
            */

            LeaveApproval::create([
                'leave_request_id' =>
                    $leave->id,

                'approver_id' =>
                    $director->user_id,

                'approval_level' =>
                    self::LEVEL_DIRECTOR,

                'status' =>
                    self::STATUS_WAITING,
            ]);

            DB::commit();

            return redirect()
                ->route(
                    'employee.leave.index'
                )
                ->with(
                    'success',
                    'Pengajuan cuti berhasil dikirim dan menunggu persetujuan Supervisor.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($attachment) {
                Storage::disk('public')
                    ->delete($attachment);
            }

            if ($employeeSignature) {
                Storage::disk('public')
                    ->delete($employeeSignature);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        LeaveRequest $leave
    ) {
        $leave = $this->getOwnedLeave(
            $leave
        );

        $leave->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ]);

        $employeeApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_EMPLOYEE
            )
            ->first();

        $supervisorApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_SUPERVISOR
            )
            ->first();

        $managerApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_MANAGER
            )
            ->first();

        $directorApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_DIRECTOR
            )
            ->first();

        return view(
            'karyawan.leave.show',
            compact(
                'leave',
                'employeeApproval',
                'supervisorApproval',
                'managerApproval',
                'directorApproval'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        LeaveRequest $leave
    ) {
        $leave = $this->getOwnedLeave(
            $leave
        );

        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $leave->status !==
            LeaveRequest::STATUS_PENDING
        ) {
            return redirect()
                ->route(
                    'employee.leave.index'
                )
                ->with(
                    'error',
                    'Pengajuan yang sudah diproses tidak dapat diedit.'
                );
        }

        $employee = $this->getEmployee();

        /*
        |--------------------------------------------------------------------------
        | LEAVE TYPES
        |--------------------------------------------------------------------------
        */

        $leaveTypes = LeaveType::query()
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE DAYS
        |--------------------------------------------------------------------------
        |
        | Request yang sedang diedit dikecualikan.
        |
        */

        $availableLeaveDays = [];

        foreach ($leaveTypes as $type) {
            $availableLeaveDays[$type->id] =
                $this->getAvailableLeaveDays(
                    $employee,
                    $type,
                    $leave->id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CURRENT YEAR
        |--------------------------------------------------------------------------
        */

        $currentYear = now()->year;

        /*
        |--------------------------------------------------------------------------
        | TOTAL QUOTA
        |--------------------------------------------------------------------------
        */

        $totalLeave = LeaveBalance::query()
            ->where(
                'employee_id',
                $employee->id
            )
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('quota');

        /*
        |--------------------------------------------------------------------------
        | USED
        |--------------------------------------------------------------------------
        */

        $usedLeave = LeaveBalance::query()
            ->where(
                'employee_id',
                $employee->id
            )
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('used');

        /*
        |--------------------------------------------------------------------------
        | REMAINING
        |--------------------------------------------------------------------------
        */

        $remainingLeave = LeaveBalance::query()
            ->where(
                'employee_id',
                $employee->id
            )
            ->where(
                'year',
                $currentYear
            )
            ->where(
                'is_active',
                true
            )
            ->sum('remaining');

        return view(
            'karyawan.leave.edit',
            compact(
                'leave',
                'employee',
                'leaveTypes',
                'totalLeave',
                'usedLeave',
                'remainingLeave',
                'availableLeaveDays'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        LeaveRequest $leave
    ) {
        $leave = $this->getOwnedLeave(
            $leave
        );

        $employee = $this->getEmployee();

        /*
        |--------------------------------------------------------------------------
        | ONLY PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $leave->status !==
            LeaveRequest::STATUS_PENDING
        ) {
            return back()
                ->with(
                    'error',
                    'Pengajuan yang sudah diproses tidak dapat diubah.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'leave_type_id' => [
                'required',
                'integer',
                'exists:leave_types,id',
            ],

            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ]);

        DB::beginTransaction();

        $oldAttachment = $leave->attachment;
        $attachment = $oldAttachment;

        try {
            /*
            |--------------------------------------------------------------------------
            | LEAVE TYPE
            |--------------------------------------------------------------------------
            */

            $leaveType = LeaveType::query()
                ->where(
                    'id',
                    $validated['leave_type_id']
                )
                ->where(
                    'is_active',
                    true
                )
                ->first();

            if (!$leaveType) {
                throw new \Exception(
                    'Jenis cuti tidak ditemukan atau tidak aktif.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CALCULATE WORKING DAYS
            |--------------------------------------------------------------------------
            */

            $totalDays = $this->calculateDays(
                $validated['start_date'],
                $validated['end_date']
            );

            /*
            |--------------------------------------------------------------------------
            | MUST HAVE WORKING DAY
            |--------------------------------------------------------------------------
            */

            if ($totalDays <= 0) {
                throw new \Exception(
                    'Rentang tanggal yang dipilih tidak memiliki hari kerja. ' .
                    'Sabtu, Minggu, dan hari libur tidak dihitung.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | MAX DAYS PER REQUEST
            |--------------------------------------------------------------------------
            */

            if (
                $leaveType->max_days !== null &&
                (int) $leaveType->max_days > 0 &&
                $totalDays > (int) $leaveType->max_days
            ) {
                throw new \Exception(
                    'Pengajuan cuti "' .
                    $leaveType->name .
                    '" maksimal ' .
                    $leaveType->max_days .
                    ' hari kerja per pengajuan. ' .
                    'Pengajuan Anda membutuhkan ' .
                    $totalDays .
                    ' hari kerja.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CURRENT YEAR
            |--------------------------------------------------------------------------
            */

            $currentYear = now()->year;

            /*
            |--------------------------------------------------------------------------
            | BALANCE
            |--------------------------------------------------------------------------
            */

            $balance = LeaveBalance::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where(
                    'year',
                    $currentYear
                )
                ->where(
                    'is_active',
                    true
                )
                ->lockForUpdate()
                ->first();

            if (!$balance) {
                throw new \Exception(
                    'Saldo cuti untuk jenis "' .
                    $leaveType->name .
                    '" tidak ditemukan.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | QUOTA
            |--------------------------------------------------------------------------
            */

            $quota = max(
                (int) $balance->quota,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | USED
            |--------------------------------------------------------------------------
            */

            $used = max(
                (int) $balance->used,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            $remaining = max(
                (int) $balance->remaining,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | PENDING OTHER REQUEST
            |--------------------------------------------------------------------------
            */

            $pendingDays = LeaveRequest::query()
                ->where(
                    'employee_id',
                    $employee->id
                )
                ->where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where(
                    'status',
                    LeaveRequest::STATUS_PENDING
                )
                ->where(
                    'id',
                    '!=',
                    $leave->id
                )
                ->sum('total_days');

            /*
            |--------------------------------------------------------------------------
            | AVAILABLE
            |--------------------------------------------------------------------------
            */

            $availableDays = max(
                $remaining - (int) $pendingDays,
                0
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK BALANCE
            |--------------------------------------------------------------------------
            */

            if ($totalDays > $availableDays) {
                throw new \Exception(
                    'Saldo cuti tidak mencukupi. ' .
                    'Quota: ' .
                    $quota .
                    ' hari, ' .
                    'sudah digunakan: ' .
                    $used .
                    ' hari, ' .
                    'sisa: ' .
                    $remaining .
                    ' hari, ' .
                    'sedang diajukan: ' .
                    (int) $pendingDays .
                    ' hari, ' .
                    'tersedia untuk pengajuan: ' .
                    $availableDays .
                    ' hari. ' .
                    'Pengajuan membutuhkan: ' .
                    $totalDays .
                    ' hari kerja.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | NEW ATTACHMENT
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('attachment')) {
                $attachment = $request
                    ->file('attachment')
                    ->store(
                        'leave-attachments',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE LEAVE
            |--------------------------------------------------------------------------
            */

            $leave->update([
                'leave_type_id' =>
                    $leaveType->id,

                'start_date' =>
                    $validated['start_date'],

                'end_date' =>
                    $validated['end_date'],

                'total_days' =>
                    $totalDays,

                'reason' =>
                    $validated['reason'],

                'attachment' =>
                    $attachment,
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | DELETE OLD ATTACHMENT
            |--------------------------------------------------------------------------
            */

            if (
                $request->hasFile('attachment') &&
                $oldAttachment
            ) {
                Storage::disk('public')
                    ->delete(
                        $oldAttachment
                    );
            }

            return redirect()
                ->route(
                    'employee.leave.index'
                )
                ->with(
                    'success',
                    'Pengajuan cuti berhasil diperbarui.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | DELETE NEW ATTACHMENT IF FAILED
            |--------------------------------------------------------------------------
            */

            if (
                $attachment &&
                $attachment !== $oldAttachment
            ) {
                Storage::disk('public')
                    ->delete(
                        $attachment
                    );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        LeaveRequest $leave
    ) {
        $leave = $this->getOwnedLeave(
            $leave
        );

        if (
            $leave->status !==
            LeaveRequest::STATUS_PENDING
        ) {
            return back()
                ->with(
                    'error',
                    'Pengajuan yang sudah diproses tidak dapat dihapus.'
                );
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | ATTACHMENT
            |--------------------------------------------------------------------------
            */

            if ($leave->attachment) {
                Storage::disk('public')
                    ->delete(
                        $leave->attachment
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | SIGNATURE
            |--------------------------------------------------------------------------
            */

            if ($leave->employee_signature_path) {
                Storage::disk('public')
                    ->delete(
                        $leave->employee_signature_path
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | APPROVALS
            |--------------------------------------------------------------------------
            */

            $leave->approvals()
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | DELETE LEAVE
            |--------------------------------------------------------------------------
            */

            $leave->delete();

            DB::commit();

            return redirect()
                ->route(
                    'employee.leave.index'
                )
                ->with(
                    'success',
                    'Pengajuan berhasil dihapus.'
                );

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->with(
                    'error',
                    'Pengajuan gagal dihapus: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf(
        LeaveRequest $leave
    ) {
        $leave = $this->getOwnedLeave(
            $leave
        );

        if (
            $leave->status !==
            LeaveRequest::STATUS_APPROVED
        ) {
            return back()
                ->with(
                    'error',
                    'PDF hanya dapat diunduh setelah seluruh proses approval selesai.'
                );
        }

        $leave->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
            'approvals.approver',
        ]);

        $employeeApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_EMPLOYEE
            )
            ->first();

        $supervisorApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_SUPERVISOR
            )
            ->first();

        $managerApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_MANAGER
            )
            ->first();

        $directorApproval = $leave->approvals
            ->where(
                'approval_level',
                self::LEVEL_DIRECTOR
            )
            ->first();

        $pdf = Pdf::loadView(
            'pdf.leave-approval',
            [
                'leave' =>
                    $leave,

                'employeeApproval' =>
                    $employeeApproval,

                'supervisorApproval' =>
                    $supervisorApproval,

                'managerApproval' =>
                    $managerApproval,

                'directorApproval' =>
                    $directorApproval,
            ]
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->download(
            'Surat_Cuti_' .
            $leave->request_number .
            '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE SIGNATURE
    |--------------------------------------------------------------------------
    */

    private function saveSignature(
        string $signature
    ): string {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE DATA URI
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                '/^data:image\/png;base64,/i',
                $signature
            )
        ) {
            throw new \Exception(
                'Format tanda tangan tidak valid.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GET BASE64
        |--------------------------------------------------------------------------
        */

        $image = substr(
            $signature,
            strpos(
                $signature,
                ','
            ) + 1
        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE WHITESPACE
        |--------------------------------------------------------------------------
        */

        $image = str_replace(
            ' ',
            '+',
            $image
        );

        /*
        |--------------------------------------------------------------------------
        | DECODE
        |--------------------------------------------------------------------------
        */

        $image = base64_decode(
            $image,
            true
        );

        if ($image === false) {
            throw new \Exception(
                'Tanda tangan tidak dapat diproses.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK IMAGE
        |--------------------------------------------------------------------------
        */

        $imageInfo = @getimagesizefromstring(
            $image
        );

        if ($imageInfo === false) {
            throw new \Exception(
                'Data tanda tangan bukan gambar yang valid.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILE NAME
        |--------------------------------------------------------------------------
        */

        $filename =
            'signature_' .
            Str::uuid() .
            '.png';

        $path =
            'signatures/' .
            $filename;

        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')
            ->put(
                $path,
                $image
            );

        return $path;
    }
}