<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HrdLeaveBalanceController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * KONSEP:
     *
     * 1 KARYAWAN = 1 BARIS
     *
     * Hak cuti tahunan:
     * 12 hari / tahun
     *
     * Used:
     * Total hari Cuti Tahunan yang sudah APPROVED.
     *
     * Remaining:
     * Quota - Used
     *
     * PENTING:
     *
     * Hanya tahun berjalan yang boleh dibuat otomatis.
     *
     * Jika memilih tahun lama / tahun mendatang:
     * - tidak membuat LeaveBalance baru
     * - hanya menampilkan data LeaveBalance yang memang
     *   sudah ada di database.
     */
    public function index(Request $request)
    {
        $year = (int) $request->input(
            'year',
            now()->year
        );

        $search = trim(
            $request->input('search', '')
        );

        $departmentId = $request->input(
            'department_id'
        );

        /*
        |--------------------------------------------------------------------------
        | CARI CUTI TAHUNAN
        |--------------------------------------------------------------------------
        */

        $annualLeaveType = LeaveType::query()
            ->where('is_active', true)
            ->where(function ($query) {

                $query
                    ->whereRaw(
                        'LOWER(name) LIKE ?',
                        ['%cuti tahunan%']
                    )
                    ->orWhereRaw(
                        'LOWER(name) LIKE ?',
                        ['%tahunan%']
                    )
                    ->orWhereRaw(
                        'LOWER(code) LIKE ?',
                        ['%tahunan%']
                    );
            })
            ->first();

        /*
        |--------------------------------------------------------------------------
        | OTOMATIS BUAT SALDO HANYA UNTUK TAHUN BERJALAN
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | Tahun sekarang = 2026
        |
        | 2026 -> boleh membuat saldo 12 hari
        |
        | 2025 -> jangan membuat saldo
        |
        | 2027 -> jangan membuat saldo
        |
        */

        if (
            $annualLeaveType &&
            $year === (int) now()->year
        ) {

            $this->ensureAnnualBalancesExist(
                $year,
                $annualLeaveType
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SYNC SALDO
        |--------------------------------------------------------------------------
        |
        | Sync hanya terhadap LeaveBalance yang memang sudah ada.
        |
        */

        if ($annualLeaveType) {

            $this->syncAnnualBalances(
                $year,
                $annualLeaveType
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUERY EMPLOYEE
        |--------------------------------------------------------------------------
        |
        | Pagination berdasarkan EMPLOYEE.
        |
        | Karena itu:
        |
        | 1 employee = 1 row
        |
        */

        $employeeQuery = Employee::query()
            ->with([
                'user',
                'department',
                'position',
            ])
            ->where('status', 'active');

        /*
        |--------------------------------------------------------------------------
        | HANYA TAMPILKAN EMPLOYEE YANG MEMILIKI SALDO TAHUN TERSEBUT
        |--------------------------------------------------------------------------
        |
        | Ini bagian penting untuk kasus tahun 2025.
        |
        | Jika database:
        |
        | leave_balances
        | year = 2025
        | tidak ada
        |
        | maka employee tidak akan muncul.
        |
        */

        if ($annualLeaveType) {

            $employeeQuery->whereHas(
                'leaveBalances',
                function ($query) use (
                    $year,
                    $annualLeaveType
                ) {

                    $query
                        ->where(
                            'year',
                            $year
                        )
                        ->where(
                            'leave_type_id',
                            $annualLeaveType->id
                        );
                }
            );
        } else {

            /*
            |--------------------------------------------------------------------------
            | Jika Cuti Tahunan tidak ditemukan
            |--------------------------------------------------------------------------
            |
            | Jangan tampilkan data saldo.
            |
            */

            $employeeQuery->whereRaw('1 = 0');
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $employeeQuery->where(
                function ($query) use ($search) {

                    $query->where(
                        'nik',
                        'like',
                        "%{$search}%"
                    );

                    $query->orWhereHas(
                        'user',
                        function ($userQuery) use (
                            $search
                        ) {

                            $userQuery->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if (!empty($departmentId)) {

            $employeeQuery->where(
                'department_id',
                $departmentId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan subquery Employee::select()
        | karena dapat menghasilkan:
        |
        | "Subquery returned more than 1 value"
        |
        */

        $employees = $employeeQuery
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL BALANCE UNTUK EMPLOYEE DI HALAMAN SAAT INI
        |--------------------------------------------------------------------------
        */

        $employeeIds = $employees
            ->getCollection()
            ->pluck('id');

        $balanceMap = collect();

        if (
            $annualLeaveType &&
            $employeeIds->isNotEmpty()
        ) {

            $balanceMap = LeaveBalance::query()
                ->where(
                    'year',
                    $year
                )
                ->where(
                    'leave_type_id',
                    $annualLeaveType->id
                )
                ->whereIn(
                    'employee_id',
                    $employeeIds
                )
                ->get()
                ->keyBy(
                    'employee_id'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | ATTACH BALANCE KE EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employees
            ->getCollection()
            ->transform(
                function ($employee) use (
                    $balanceMap
                ) {

                    $balance = $balanceMap->get(
                        $employee->id
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | DATA SALDO
                    |--------------------------------------------------------------------------
                    */

                    $quota = $balance
                        ? (int) $balance->quota
                        : 0;

                    $used = $balance
                        ? (int) $balance->used
                        : 0;

                    $carryForward = $balance
                        ? (int) $balance->carry_forward
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | REMAINING
                    |--------------------------------------------------------------------------
                    */

                    $remaining = max(
                        0,
                        $quota - $used
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | PERCENTAGE
                    |--------------------------------------------------------------------------
                    */

                    $percentage = $quota > 0
                        ? min(
                            100,
                            round(
                                ($used / $quota) * 100
                            )
                        )
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | VIRTUAL ATTRIBUTE
                    |--------------------------------------------------------------------------
                    */

                    $employee->leave_balance =
                        $balance;

                    $employee->leave_quota =
                        $quota;

                    $employee->leave_used =
                        $used;

                    $employee->leave_remaining =
                        $remaining;

                    $employee->leave_carry_forward =
                        $carryForward;

                    $employee->leave_percentage =
                        $percentage;

                    return $employee;
                }
            );

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        |
        | Summary dihitung berdasarkan EMPLOYEE yang
        | memenuhi filter + LeaveBalance tahun tersebut.
        |
        */

        $summaryEmployeeQuery = Employee::query()
            ->where('status', 'active');

        /*
        |--------------------------------------------------------------------------
        | HANYA EMPLOYEE YANG MEMILIKI BALANCE TAHUN TERPILIH
        |--------------------------------------------------------------------------
        */

        if ($annualLeaveType) {

            $summaryEmployeeQuery->whereHas(
                'leaveBalances',
                function ($query) use (
                    $year,
                    $annualLeaveType
                ) {

                    $query
                        ->where(
                            'year',
                            $year
                        )
                        ->where(
                            'leave_type_id',
                            $annualLeaveType->id
                        );
                }
            );
        } else {

            $summaryEmployeeQuery->whereRaw(
                '1 = 0'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH SUMMARY
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $summaryEmployeeQuery->where(
                function ($query) use ($search) {

                    $query->where(
                        'nik',
                        'like',
                        "%{$search}%"
                    );

                    $query->orWhereHas(
                        'user',
                        function ($userQuery) use (
                            $search
                        ) {

                            $userQuery->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT SUMMARY
        |--------------------------------------------------------------------------
        */

        if (!empty($departmentId)) {

            $summaryEmployeeQuery->where(
                'department_id',
                $departmentId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE ID SUMMARY
        |--------------------------------------------------------------------------
        */

        $summaryEmployeeIds =
            $summaryEmployeeQuery
                ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SUMMARY
        |--------------------------------------------------------------------------
        */

        $summary = [
            'employees' => 0,
            'quota' => 0,
            'used' => 0,
            'remaining' => 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | HITUNG SUMMARY BALANCE
        |--------------------------------------------------------------------------
        */

        if (
            $annualLeaveType &&
            $summaryEmployeeIds->isNotEmpty()
        ) {

            $summaryBalance =
                LeaveBalance::query()
                    ->where(
                        'year',
                        $year
                    )
                    ->where(
                        'leave_type_id',
                        $annualLeaveType->id
                    )
                    ->whereIn(
                        'employee_id',
                        $summaryEmployeeIds
                    )
                    ->get();

            /*
            |--------------------------------------------------------------------------
            | TOTAL EMPLOYEE
            |--------------------------------------------------------------------------
            */

            $summary['employees'] =
                $summaryEmployeeIds->count();

            /*
            |--------------------------------------------------------------------------
            | TOTAL QUOTA
            |--------------------------------------------------------------------------
            */

            $summary['quota'] =
                $summaryBalance->sum(
                    fn ($balance) =>
                        (int) $balance->quota
                );

            /*
            |--------------------------------------------------------------------------
            | TOTAL USED
            |--------------------------------------------------------------------------
            */

            $summary['used'] =
                $summaryBalance->sum(
                    fn ($balance) =>
                        (int) $balance->used
                );

            /*
            |--------------------------------------------------------------------------
            | TOTAL REMAINING
            |--------------------------------------------------------------------------
            */

            $summary['remaining'] =
                $summaryBalance->sum(
                    fn ($balance) =>
                        max(
                            0,
                            (int) $balance->quota
                            -
                            (int) $balance->used
                        )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER DATA
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where(
                'is_active',
                true
            )
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.leave-balances.index',
            compact(
                'employees',
                'year',
                'search',
                'departmentId',
                'departments',
                'summary',
                'annualLeaveType'
            )
        );
    }


    /**
     * =========================================================
     * EDIT
     * =========================================================
     */
    public function edit(
        LeaveBalance $leaveBalance
    ) {

        /*
        |--------------------------------------------------------------------------
        | SYNC TERLEBIH DAHULU
        |--------------------------------------------------------------------------
        */

        $this->syncAnnualBalances(
            $leaveBalance->year,
            $leaveBalance->leaveType
        );

        /*
        |--------------------------------------------------------------------------
        | REFRESH
        |--------------------------------------------------------------------------
        */

        $leaveBalance->refresh();

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONSHIP
        |--------------------------------------------------------------------------
        */

        $leaveBalance->load([
            'employee.user',
            'employee.department',
            'employee.position',
            'leaveType',
        ]);

        return view(
            'hrd.leave-balances.edit',
            compact(
                'leaveBalance'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(
        Request $request,
        LeaveBalance $leaveBalance
    ) {

        /*
        |--------------------------------------------------------------------------
        | SYNC TERLEBIH DAHULU
        |--------------------------------------------------------------------------
        */

        $this->syncAnnualBalances(
            $leaveBalance->year,
            $leaveBalance->leaveType
        );

        $leaveBalance->refresh();

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [

                'quota' => [
                    'required',
                    'integer',
                    'min:' . (int) $leaveBalance->used,
                    'max:365',
                ],

                'carry_forward' => [
                    'nullable',
                    'integer',
                    'min:0',
                    'max:365',
                ],

            ],
            [

                'quota.required' =>
                    'Hak cuti wajib diisi.',

                'quota.integer' =>
                    'Hak cuti harus berupa angka.',

                'quota.min' =>
                    'Hak cuti tidak boleh lebih kecil dari jumlah cuti yang sudah digunakan.',

                'quota.max' =>
                    'Hak cuti maksimal 365 hari.',

                'carry_forward.integer' =>
                    'Carry forward harus berupa angka.',

                'carry_forward.min' =>
                    'Carry forward tidak boleh kurang dari 0.',

                'carry_forward.max' =>
                    'Carry forward maksimal 365 hari.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $leaveBalance,
                $validated
            ) {

                $quota =
                    (int) $validated['quota'];

                $used =
                    (int) $leaveBalance->used;

                $carryForward =
                    (int) (
                        $validated['carry_forward']
                        ?? 0
                    );

                /*
                |--------------------------------------------------------------------------
                | REMAINING
                |--------------------------------------------------------------------------
                */

                $remaining = max(
                    0,
                    $quota - $used
                );

                $leaveBalance->update(
                    [

                        'quota' =>
                            $quota,

                        'used' =>
                            $used,

                        'remaining' =>
                            $remaining,

                        'carry_forward' =>
                            $carryForward,

                    ]
                );
            }
        );

        return redirect()
            ->route(
                'hrd.leave-balances.index',
                [
                    'year' =>
                        $leaveBalance->year,
                ]
            )
            ->with(
                'success',
                'Saldo cuti berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * ENSURE ANNUAL BALANCES
     * =========================================================
     *
     * HANYA dipanggil untuk tahun berjalan.
     *
     * Contoh:
     *
     * Tahun sekarang = 2026
     *
     * Employee A
     * Cuti Tahunan
     * 2026
     * quota = 12
     *
     * Tidak akan membuat:
     *
     * Employee A
     * Cuti Tahunan
     * 2025
     *
     * secara otomatis.
     */
    private function ensureAnnualBalancesExist(
        int $year,
        LeaveType $annualLeaveType
    ): void {

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        |
        | Fungsi ini hanya boleh bekerja untuk tahun berjalan.
        |
        */

        if ($year !== (int) now()->year) {
            return;
        }

        $employees = Employee::query()
            ->where(
                'status',
                'active'
            )
            ->get();

        foreach ($employees as $employee) {

            LeaveBalance::firstOrCreate(
                [

                    'employee_id' =>
                        $employee->id,

                    'leave_type_id' =>
                        $annualLeaveType->id,

                    'year' =>
                        $year,

                ],
                [

                    'quota' =>
                        12,

                    'used' =>
                        0,

                    'remaining' =>
                        12,

                    'carry_forward' =>
                        0,

                    'is_active' =>
                        true,

                ]
            );
        }
    }


    /**
     * =========================================================
     * SYNC ANNUAL BALANCES
     * =========================================================
     *
     * Used = total hari Cuti Tahunan APPROVED.
     *
     * Contoh:
     *
     * Quota = 12
     *
     * Pengajuan 1 = 3 hari
     * Pengajuan 2 = 2 hari
     *
     * Used = 5
     *
     * Remaining = 12 - 5
     * Remaining = 7
     */
    private function syncAnnualBalances(
        int $year,
        ?LeaveType $annualLeaveType
    ): void {

        if (!$annualLeaveType) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL BALANCE YANG BENAR-BENAR ADA
        |--------------------------------------------------------------------------
        */

        $balances = LeaveBalance::query()
            ->where(
                'year',
                $year
            )
            ->where(
                'leave_type_id',
                $annualLeaveType->id
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JIKA TIDAK ADA BALANCE
        |--------------------------------------------------------------------------
        |
        | Jangan membuat apa pun.
        |
        */

        if ($balances->isEmpty()) {
            return;
        }

        foreach ($balances as $balance) {

            /*
            |--------------------------------------------------------------------------
            | HITUNG CUTI TAHUNAN APPROVED
            |--------------------------------------------------------------------------
            */

            $used = LeaveRequest::query()
                ->where(
                    'employee_id',
                    $balance->employee_id
                )
                ->where(
                    'leave_type_id',
                    $annualLeaveType->id
                )
                ->where(
                    'status',
                    LeaveRequest::STATUS_APPROVED
                )
                ->whereYear(
                    'start_date',
                    $year
                )
                ->sum(
                    'total_days'
                );

            $used = (int) $used;

            /*
            |--------------------------------------------------------------------------
            | QUOTA
            |--------------------------------------------------------------------------
            |
            | Jangan selalu dipaksa 12.
            |
            | Kalau HRD sudah mengubah quota,
            | nilai tersebut tetap digunakan.
            |
            */

            $quota = max(
                0,
                (int) $balance->quota
            );

            /*
            |--------------------------------------------------------------------------
            | REMAINING
            |--------------------------------------------------------------------------
            */

            $remaining = max(
                0,
                $quota - $used
            );

            /*
            |--------------------------------------------------------------------------
            | UPDATE JIKA ADA PERUBAHAN
            |--------------------------------------------------------------------------
            */

            if (
                (int) $balance->used !== $used
                ||
                (int) $balance->remaining !== $remaining
            ) {

                $balance->update(
                    [

                        'used' =>
                            $used,

                        'remaining' =>
                            $remaining,

                    ]
                );
            }
        }
    }
}