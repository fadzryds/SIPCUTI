<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
     * Jumlah employee yang berhasil dibuat.
     */
    protected int $successCount = 0;

    /**
     * Import seluruh data employee.
     */
    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'file' => 'File Excel tidak memiliki data employee.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. VALIDASI HEADER
        |--------------------------------------------------------------------------
        */

        $requiredColumns = [
            'nik',
            'name',
            'email',
            'phone',
            'password',
            'role',
            'department_code',
            'position_code',
            'join_date',
            'birth_date',
            'gender',
            'address',
            'supervisor_nik',
            'manager_nik',
            'director_nik',
            'status',
        ];

        $firstRow = $rows->first();

        foreach ($requiredColumns as $column) {

            if (!array_key_exists($column, $firstRow->toArray())) {

                throw ValidationException::withMessages([
                    'file' =>
                        "Kolom '{$column}' tidak ditemukan dalam file Excel.",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. NORMALISASI DATA
        |--------------------------------------------------------------------------
        */

        $normalizedRows = collect();

        foreach ($rows as $index => $row) {

            /*
             * Excel row dimulai dari baris 2 karena
             * baris 1 adalah header.
             */
            $excelRow = $index + 2;

            $data = [

                'nik' => $this->clean(
                    $row['nik'] ?? null
                ),

                'name' => $this->clean(
                    $row['name'] ?? null
                ),

                'email' => strtolower(
                    $this->clean($row['email'] ?? null)
                ),

                'phone' => $this->clean(
                    $row['phone'] ?? null
                ),

                'password' => $this->clean(
                    $row['password'] ?? null
                ),

                'role' => $this->clean(
                    $row['role'] ?? null
                ),

                'department_code' => strtoupper(
                    $this->clean($row['department_code'] ?? null)
                ),

                'position_code' => strtoupper(
                    $this->clean($row['position_code'] ?? null)
                ),

                'join_date' => $this->normalizeDate(
                    $row['join_date'] ?? null
                ),

                'birth_date' => $this->normalizeDate(
                    $row['birth_date'] ?? null
                ),

                'gender' => $this->clean(
                    $row['gender'] ?? null
                ),

                'address' => $this->clean(
                    $row['address'] ?? null
                ),

                'supervisor_nik' => $this->clean(
                    $row['supervisor_nik'] ?? null
                ),

                'manager_nik' => $this->clean(
                    $row['manager_nik'] ?? null
                ),

                'director_nik' => $this->clean(
                    $row['director_nik'] ?? null
                ),

                'status' => $this->clean(
                    $row['status'] ?? null
                ),

                '_excel_row' => $excelRow,
            ];

            /*
            |--------------------------------------------------------------------------
            | BARIS KOSONG
            |--------------------------------------------------------------------------
            */

            if (
                $data['nik'] === '' &&
                $data['name'] === '' &&
                $data['email'] === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI BARIS
            |--------------------------------------------------------------------------
            */

            $this->validateRow($data);

            $normalizedRows->push($data);
        }

        if ($normalizedRows->isEmpty()) {

            throw ValidationException::withMessages([
                'file' => 'Tidak terdapat data employee yang dapat diimport.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. CEK DUPLIKAT NIK DI EXCEL
        |--------------------------------------------------------------------------
        */

        $duplicateNiks = $normalizedRows
            ->pluck('nik')
            ->duplicates()
            ->values();

        if ($duplicateNiks->isNotEmpty()) {

            throw ValidationException::withMessages([
                'file' =>
                    'Terdapat NIK duplikat di dalam Excel: ' .
                    $duplicateNiks->implode(', '),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. CEK DUPLIKAT EMAIL DI EXCEL
        |--------------------------------------------------------------------------
        */

        $duplicateEmails = $normalizedRows
            ->pluck('email')
            ->duplicates()
            ->values();

        if ($duplicateEmails->isNotEmpty()) {

            throw ValidationException::withMessages([
                'file' =>
                    'Terdapat email duplikat di dalam Excel: ' .
                    $duplicateEmails->implode(', '),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5. CEK NIK TERHADAP DATABASE
        |--------------------------------------------------------------------------
        |
        | NIK yang sedang dibuat TIDAK BOLEH sudah ada di database.
        |
        | Tetapi NIK tersebut tetap BOLEH digunakan sebagai:
        |
        | supervisor_nik
        | manager_nik
        | director_nik
        |
        */

        $importNiks = $normalizedRows
            ->pluck('nik')
            ->filter()
            ->values()
            ->all();

        $existingEmployees = Employee::query()
            ->whereIn('nik', $importNiks)
            ->pluck('nik')
            ->all();

        if (!empty($existingEmployees)) {

            throw ValidationException::withMessages([
                'file' =>
                    'Import dibatalkan. NIK berikut sudah terdaftar di database: ' .
                    implode(', ', $existingEmployees) .
                    '. NIK yang sudah ada tidak boleh diimport ulang.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. CEK EMAIL TERHADAP DATABASE
        |--------------------------------------------------------------------------
        */

        $importEmails = $normalizedRows
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        $existingEmails = User::query()
            ->whereIn('email', $importEmails)
            ->pluck('email')
            ->all();

        if (!empty($existingEmails)) {

            throw ValidationException::withMessages([
                'file' =>
                    'Import dibatalkan. Email berikut sudah terdaftar di database: ' .
                    implode(', ', $existingEmails),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 7. LOAD DEPARTMENT
        |--------------------------------------------------------------------------
        */

        $departmentCodes = $normalizedRows
            ->pluck('department_code')
            ->filter()
            ->unique()
            ->values();

        $departments = Department::query()
            ->whereIn('code', $departmentCodes)
            ->get()
            ->keyBy('code');

        foreach ($normalizedRows as $data) {

            if ($data['department_code'] === '') {
                continue;
            }

            if (!$departments->has($data['department_code'])) {

                throw ValidationException::withMessages([
                    'file' =>
                        "Baris {$data['_excel_row']}: Department dengan code '{$data['department_code']}' tidak ditemukan.",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 8. LOAD POSITION
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Satu position boleh dimiliki banyak employee.
        |
        | Contoh:
        |
        | ENG
        | ENG
        | ENG
        |
        | semuanya valid.
        |
        */

        $positionCodes = $normalizedRows
            ->pluck('position_code')
            ->filter()
            ->unique()
            ->values();

        $positions = Position::query()
            ->whereIn('code', $positionCodes)
            ->get()
            ->keyBy('code');

        foreach ($normalizedRows as $data) {

            if ($data['position_code'] === '') {
                continue;
            }

            if (!$positions->has($data['position_code'])) {

                throw ValidationException::withMessages([
                    'file' =>
                        "Baris {$data['_excel_row']}: Position dengan code '{$data['position_code']}' tidak ditemukan.",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. VALIDASI HIERARCHY SEBELUM CREATE
        |--------------------------------------------------------------------------
        |
        | Relasi dapat berasal dari:
        |
        | A. Employee baru di Excel
        | B. Employee yang sudah ada di database
        |
        */

        $this->validateHierarchyReferences(
            $normalizedRows
        );

        /*
        |--------------------------------------------------------------------------
        | 10. TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $normalizedRows,
            $departments,
            $positions
        ) {

            /*
            |--------------------------------------------------------------------------
            | MAP EMPLOYEE BARU
            |--------------------------------------------------------------------------
            */

            $employeeMap = [];

            /*
            |--------------------------------------------------------------------------
            | 11. CREATE USER + EMPLOYEE
            |--------------------------------------------------------------------------
            */

            foreach ($normalizedRows as $data) {

                $department = null;

                if ($data['department_code'] !== '') {

                    $department =
                        $departments->get(
                            $data['department_code']
                        );
                }

                $position = null;

                if ($data['position_code'] !== '') {

                    $position =
                        $positions->get(
                            $data['position_code']
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE USER
                |--------------------------------------------------------------------------
                */

                $user = User::create([

                    'name' =>
                        $data['name'],

                    'email' =>
                        $data['email'],

                    'phone' =>
                        $data['phone'] !== ''
                            ? $data['phone']
                            : null,

                    'password' =>
                        Hash::make(
                            $data['password']
                        ),
                ]);

                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                $user->assignRole(
                    $data['role']
                );

                /*
                |--------------------------------------------------------------------------
                | CREATE EMPLOYEE
                |--------------------------------------------------------------------------
                */

                $employee = Employee::create([

                    'user_id' =>
                        $user->id,

                    'nik' =>
                        $data['nik'],

                    'department_id' =>
                        $department?->id,

                    'position_id' =>
                        $position?->id,

                    /*
                    |--------------------------------------------------------------
                    | HIERARCHY
                    |--------------------------------------------------------------
                    */

                    'supervisor_id' =>
                        null,

                    'manager_id' =>
                        null,

                    'director_id' =>
                        null,

                    'join_date' =>
                        $data['join_date'],

                    'birth_date' =>
                        $data['birth_date'],

                    'gender' =>
                        $data['gender'],

                    'address' =>
                        $data['address'] !== ''
                            ? $data['address']
                            : null,

                    'status' =>
                        $data['status'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | SIMPAN MAP
                |--------------------------------------------------------------------------
                */

                $employeeMap[$data['nik']] =
                    $employee;

                $this->successCount++;
            }

            /*
            |--------------------------------------------------------------------------
            | 12. SET HIERARCHY
            |--------------------------------------------------------------------------
            */

            foreach ($normalizedRows as $data) {

                /** @var Employee $employee */
                $employee =
                    $employeeMap[$data['nik']];

                /*
                |--------------------------------------------------------------------------
                | EMPLOYEE → SUPERVISOR
                |--------------------------------------------------------------------------
                */

                if ($data['supervisor_nik'] !== '') {

                    $supervisor =
                        $this->findEmployeeByNik(
                            $data['supervisor_nik'],
                            $employeeMap
                        );

                    $employee->supervisor_id =
                        $supervisor->id;
                }

                /*
                |--------------------------------------------------------------------------
                | SUPERVISOR → MANAGER
                |--------------------------------------------------------------------------
                */

                if ($data['manager_nik'] !== '') {

                    $manager =
                        $this->findEmployeeByNik(
                            $data['manager_nik'],
                            $employeeMap
                        );

                    $employee->manager_id =
                        $manager->id;
                }

                /*
                |--------------------------------------------------------------------------
                | MANAGER → DIRECTOR
                |--------------------------------------------------------------------------
                */

                if ($data['director_nik'] !== '') {

                    $director =
                        $this->findEmployeeByNik(
                            $data['director_nik'],
                            $employeeMap
                        );

                    $employee->director_id =
                        $director->id;
                }

                /*
                |--------------------------------------------------------------------------
                | SAVE
                |--------------------------------------------------------------------------
                */

                $employee->save();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI HIERARCHY
    |--------------------------------------------------------------------------
    */

    protected function validateHierarchyReferences(
        Collection $rows
    ): void {

        foreach ($rows as $data) {

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE → SUPERVISOR
            |--------------------------------------------------------------------------
            */

            if ($data['supervisor_nik'] !== '') {

                $supervisor =
                    $this->findEmployeeByNikForValidation(
                        $data['supervisor_nik'],
                        $rows
                    );

                if (!$supervisor) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Supervisor dengan NIK '{$data['supervisor_nik']}' tidak ditemukan di Excel maupun database.",
                    ]);
                }

                if (
                    $supervisor['role'] !== 'Supervisor'
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: NIK '{$data['supervisor_nik']}' harus memiliki role Supervisor.",
                    ]);
                }

                if (
                    $data['nik'] ===
                    $data['supervisor_nik']
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Employee tidak boleh menjadi supervisor dirinya sendiri.",
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR → MANAGER
            |--------------------------------------------------------------------------
            */

            if ($data['manager_nik'] !== '') {

                $manager =
                    $this->findEmployeeByNikForValidation(
                        $data['manager_nik'],
                        $rows
                    );

                if (!$manager) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Manager dengan NIK '{$data['manager_nik']}' tidak ditemukan di Excel maupun database.",
                    ]);
                }

                if (
                    $manager['role'] !== 'Manager'
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: NIK '{$data['manager_nik']}' harus memiliki role Manager.",
                    ]);
                }

                if (
                    $data['nik'] ===
                    $data['manager_nik']
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Employee tidak boleh menjadi manager dirinya sendiri.",
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MANAGER → DIRECTOR
            |--------------------------------------------------------------------------
            */

            if ($data['director_nik'] !== '') {

                $director =
                    $this->findEmployeeByNikForValidation(
                        $data['director_nik'],
                        $rows
                    );

                if (!$director) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Director dengan NIK '{$data['director_nik']}' tidak ditemukan di Excel maupun database.",
                    ]);
                }

                if (
                    $director['role'] !== 'Director'
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: NIK '{$data['director_nik']}' harus memiliki role Director.",
                    ]);
                }

                if (
                    $data['nik'] ===
                    $data['director_nik']
                ) {

                    throw ValidationException::withMessages([
                        'file' =>
                            "Baris {$data['_excel_row']}: Employee tidak boleh menjadi director dirinya sendiri.",
                    ]);
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CARI EMPLOYEE UNTUK VALIDASI
    |--------------------------------------------------------------------------
    |
    | Prioritas:
    |
    | 1. Excel
    | 2. Database
    |
    */

    protected function findEmployeeByNikForValidation(
        string $nik,
        Collection $rows
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | CARI DI EXCEL
        |--------------------------------------------------------------------------
        */

        $excelEmployee =
            $rows->firstWhere(
                'nik',
                $nik
            );

        if ($excelEmployee) {
            return $excelEmployee;
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DI DATABASE
        |--------------------------------------------------------------------------
        */

        $employee =
            Employee::query()
                ->with('user')
                ->where('nik', $nik)
                ->first();

        if (!$employee) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL ROLE DARI USER
        |--------------------------------------------------------------------------
        */

        $role = null;

        if (
            $employee->user &&
            method_exists(
                $employee->user,
                'getRoleNames'
            )
        ) {

            $role =
                $employee->user
                    ->getRoleNames()
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN FORMAT
        |--------------------------------------------------------------------------
        */

        return [

            'nik' =>
                $employee->nik,

            'name' =>
                $employee->user?->name,

            'role' =>
                $role,

            '_database' =>
                true,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CARI EMPLOYEE SAAT SET HIERARCHY
    |--------------------------------------------------------------------------
    */

    protected function findEmployeeByNik(
        string $nik,
        array $employeeMap
    ): Employee {

        /*
        |--------------------------------------------------------------------------
        | 1. CARI EMPLOYEE BARU DI EXCEL
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $employeeMap[$nik]
            )
        ) {

            return $employeeMap[$nik];
        }

        /*
        |--------------------------------------------------------------------------
        | 2. CARI EMPLOYEE LAMA DI DATABASE
        |--------------------------------------------------------------------------
        */

        $employee =
            Employee::query()
                ->where('nik', $nik)
                ->first();

        if (!$employee) {

            throw ValidationException::withMessages([
                'file' =>
                    "NIK '{$nik}' tidak ditemukan di database.",
            ]);
        }

        return $employee;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI SATU BARIS
    |--------------------------------------------------------------------------
    */

    protected function validateRow(
        array $data
    ): void {

        $row =
            $data['_excel_row'];

        /*
        |--------------------------------------------------------------------------
        | NIK
        |--------------------------------------------------------------------------
        */

        if (
            $data['nik'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: NIK wajib diisi.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NAME
        |--------------------------------------------------------------------------
        */

        if (
            $data['name'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Nama wajib diisi.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        if (
            $data['email'] === '' ||
            !filter_var(
                $data['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Email '{$data['email']}' tidak valid.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            $data['password'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Password wajib diisi.",
            ]);
        }

        if (
            strlen(
                $data['password']
            ) < 8
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Password minimal 8 karakter.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $allowedRoles = [

            'Employee',
            'Supervisor',
            'Manager',
            'Director',

        ];

        if (
            !in_array(
                $data['role'],
                $allowedRoles,
                true
            )
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Role '{$data['role']}' tidak valid. Gunakan Employee, Supervisor, Manager, atau Director.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | JOIN DATE
        |--------------------------------------------------------------------------
        */

        if (
            !$data['join_date']
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: join_date wajib diisi dan harus berupa tanggal yang valid.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GENDER
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $data['gender'],
                ['Male', 'Female'],
                true
            )
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Gender harus Male atau Female.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $data['status'],
                ['Active', 'Inactive'],
                true
            )
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Status harus Active atau Inactive.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        if (
            $data['role'] === 'Employee' &&
            $data['supervisor_nik'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Employee '{$data['name']}' wajib memiliki supervisor_nik.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        if (
            $data['role'] === 'Supervisor' &&
            $data['manager_nik'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Supervisor '{$data['name']}' wajib memiliki manager_nik.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        if (
            $data['role'] === 'Manager' &&
            $data['director_nik'] === ''
        ) {

            throw ValidationException::withMessages([
                'file' =>
                    "Baris {$row}: Manager '{$data['name']}' wajib memiliki director_nik.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | DIRECTOR
        |--------------------------------------------------------------------------
        |
        | Director tidak membutuhkan atasan.
        |
        */
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN VALUE
    |--------------------------------------------------------------------------
    */

    protected function clean(
        mixed $value
    ): string {

        if (
            $value === null
        ) {

            return '';
        }

        return trim(
            (string) $value
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATE
    |--------------------------------------------------------------------------
    */

    protected function normalizeDate(
        mixed $value
    ): ?string {

        if (
            $value === null ||
            $value === ''
        ) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | EXCEL SERIAL DATE
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric($value)
        ) {

            try {

                return \PhpOffice\PhpSpreadsheet\Shared\Date
                    ::excelToDateTimeObject(
                        $value
                    )
                    ->format('Y-m-d');

            } catch (\Throwable $e) {

                return null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STRING DATE
        |--------------------------------------------------------------------------
        */

        try {

            return \Carbon\Carbon::parse(
                $value
            )->format('Y-m-d');

        } catch (\Throwable $e) {

            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS COUNT
    |--------------------------------------------------------------------------
    */

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }
}