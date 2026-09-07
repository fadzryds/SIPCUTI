<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeeImport;

class HrdEmployeeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $query = Employee::query()
            ->with([
                'user',
                'department',
                'position',
                'manager.user',
                'supervisor.user',
                'director.user',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('nik', 'like', '%' . $search . '%')

                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');

                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {

            $query->where(
                'department_id',
                $request->department_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER POSITION
        |--------------------------------------------------------------------------
        */

        if ($request->filled('position_id')) {

            $query->where(
                'position_id',
                $request->position_id
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
        | EMPLOYEES
        |--------------------------------------------------------------------------
        */

        $employees = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

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

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalEmployees = Employee::query()
            ->count();

        $activeEmployees = Employee::query()
            ->where('status', 'Active')
            ->count();

        $inactiveEmployees = Employee::query()
            ->where('status', '!=', 'Active')
            ->count();

        return view(
            'hrd.employees.index',
            compact(
                'employees',
                'departments',
                'positions',
                'totalEmployees',
                'activeEmployees',
                'inactiveEmployees'
            )
        );
    }

    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | POSITIONS
        |--------------------------------------------------------------------------
        */

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SUPERVISORS
        |--------------------------------------------------------------------------
        |
        | Hanya employee aktif yang memiliki role Supervisor.
        |
        */

        $supervisors = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->role('Supervisor');
            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MANAGERS
        |--------------------------------------------------------------------------
        |
        | Hanya employee aktif yang memiliki role Manager.
        |
        */

        $managers = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->role('Manager');
            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();

        $directors = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->whereHas('user', function ($query) {
                $query->role('Director');
            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.employees.create',
            compact(
                'departments',
                'positions',
                'supervisors',
                'managers',
                'directors'
            )
        );
    } 

    public function store(Request $request): RedirectResponse
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        'nik' => [
            'required',
            'string',
            'max:50',
            'unique:employees,nik',
        ],

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:30',
        ],

        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        'role' => [
            'required',
            Rule::in([
                'Employee',
                'Supervisor',
                'Manager',
                'Director',
            ]),
        ],

        /*
        |--------------------------------------------------------------------------
        | ORGANIZATION
        |--------------------------------------------------------------------------
        */

        'department_id' => [
            'required',
            'integer',
            'exists:departments,id',
        ],

        'position_id' => [
            'required',
            'integer',
            'exists:positions,id',
        ],

        'supervisor_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
        ],

        'manager_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
        ],

        'director_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
        ],

        /*
        |--------------------------------------------------------------------------
        | PERSONAL
        |--------------------------------------------------------------------------
        */

        'join_date' => [
            'required',
            'date',
        ],

        'birth_date' => [
            'nullable',
            'date',
            'before:today',
        ],

        'gender' => [
            'required',
            Rule::in([
                'Male',
                'Female',
            ]),
        ],

        'address' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'status' => [
            'required',
            Rule::in([
                'Active',
                'Inactive',
            ]),
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | HIERARCHY VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $validated['role'] === 'Employee'
        && empty($validated['supervisor_id'])
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Employee wajib memiliki Supervisor.'
            );
    }

    if (
        $validated['role'] === 'Supervisor'
        && empty($validated['manager_id'])
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Supervisor wajib memiliki Manager.'
            );
    }

    if (
        $validated['role'] === 'Manager'
        && empty($validated['director_id'])
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Manager wajib memiliki Director.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        $roleExists = \Spatie\Permission\Models\Role::query()
            ->where('name', $validated['role'])
            ->where('guard_name', 'web')
            ->exists();

        if (!$roleExists) {

            throw new \RuntimeException(
                'Role ' . $validated['role'] .
                ' belum tersedia pada database.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(
                $validated['password']
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | PHONE
        |--------------------------------------------------------------------------
        */

        if (
            array_key_exists(
                'phone',
                $validated
            )
        ) {
            $userData['phone'] =
                $validated['phone'] ?? null;
        }


        $user = User::create(
            $userData
        );


        /*
        |--------------------------------------------------------------------------
        | ASSIGN ROLE
        |--------------------------------------------------------------------------
        */

        $user->assignRole(
            $validated['role']
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
                trim($validated['nik']),

            'department_id' =>
                (int) $validated['department_id'],

            'position_id' =>
                (int) $validated['position_id'],

            'supervisor_id' =>
                !empty($validated['supervisor_id'])
                    ? (int) $validated['supervisor_id']
                    : null,

            'manager_id' =>
                !empty($validated['manager_id'])
                    ? (int) $validated['manager_id']
                    : null,

            'director_id' =>
                !empty($validated['director_id'])
                    ? (int) $validated['director_id']
                    : null,

            'join_date' =>
                $validated['join_date'],

            'birth_date' =>
                !empty($validated['birth_date'])
                    ? $validated['birth_date']
                    : null,

            'gender' =>
                $validated['gender'],

            'address' =>
                $validated['address'] ?? null,

            'status' =>
                $validated['status'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION RESULT
        |--------------------------------------------------------------------------
        */

        if (!$user->exists) {

            throw new \RuntimeException(
                'User gagal dibuat.'
            );
        }

        if (!$employee->exists) {

            throw new \RuntimeException(
                'Employee gagal dibuat.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Employee berhasil dibuat secara manual.',
            [
                'employee_id' =>
                    $employee->id,

                'user_id' =>
                    $user->id,

                'nik' =>
                    $employee->nik,

                'email' =>
                    $user->email,

                'role' =>
                    $validated['role'],

                'supervisor_id' =>
                    $employee->supervisor_id,

                'manager_id' =>
                    $employee->manager_id,

                'director_id' =>
                    $employee->director_id,

                'created_by' =>
                    auth()->id(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'hrd.employees.index'
            )
            ->with(
                'success',
                'Data employee berhasil ditambahkan.'
            );


    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::rollBack();


        /*
        |--------------------------------------------------------------------------
        | LOG ERROR
        |--------------------------------------------------------------------------
        */

        Log::error(
            'GAGAL MENAMBAHKAN EMPLOYEE',
            [
                'message' =>
                    $e->getMessage(),

                'exception' =>
                    get_class($e),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine(),

                'user_id' =>
                    auth()->id(),

                'request' => [
                    'nik' =>
                        $request->input('nik'),

                    'name' =>
                        $request->input('name'),

                    'email' =>
                        $request->input('email'),

                    'phone' =>
                        $request->input('phone'),

                    'role' =>
                        $request->input('role'),

                    'department_id' =>
                        $request->input('department_id'),

                    'position_id' =>
                        $request->input('position_id'),

                    'supervisor_id' =>
                        $request->input('supervisor_id'),

                    'manager_id' =>
                        $request->input('manager_id'),

                    'director_id' =>
                        $request->input('director_id'),

                    'join_date' =>
                        $request->input('join_date'),

                    'birth_date' =>
                        $request->input('birth_date'),

                    'gender' =>
                        $request->input('gender'),

                    'status' =>
                        $request->input('status'),
                ],

                'trace' =>
                    $e->getTraceAsString(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | ERROR RESPONSE
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->with(
                'error',
                'Data employee gagal disimpan: ' .
                $e->getMessage()
            );
    }
}

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Employee $employee): View
    {
        $employee->load([
            'user',
            'department',
            'position',
            'manager.user',
            'manager.department',
            'manager.position',
            'supervisor.user',
            'supervisor.department',
            'supervisor.position',
            'director.user',
            'director.department',
            'director.position',
        ]);

        return view(
            'hrd.employees.show',
            compact('employee')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Employee $employee): View
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD CURRENT EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee->load([
            'user',
            'department',
            'position',
            'manager.user',
            'manager.department',
            'manager.position',
            'supervisor.user',
            'supervisor.department',
            'supervisor.position',
            'director.user',
            'director.department',
            'director.position',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | POSITIONS
        |--------------------------------------------------------------------------
        */

        $positions = Position::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SUPERVISORS
        |--------------------------------------------------------------------------
        */

        $supervisors = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->where('id', '!=', $employee->id)
            ->whereHas('user', function ($query) {

                $query->role('Supervisor');

            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MANAGERS
        |--------------------------------------------------------------------------
        */

        $managers = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->where('id', '!=', $employee->id)
            ->whereHas('user', function ($query) {

                $query->role('Manager');

            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DIRECTORS
        |--------------------------------------------------------------------------
        */

        $directors = Employee::query()
            ->with('user')
            ->where('status', 'Active')
            ->where('id', '!=', $employee->id)
            ->whereHas('user', function ($query) {

                $query->role('Director');

            })
            ->orderBy(
                User::select('name')
                    ->whereColumn(
                        'users.id',
                        'employees.user_id'
                    )
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.employees.edit',
            compact(
                'employee',
                'departments',
                'positions',
                'supervisors',
                'managers',
                'directors'
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
        Employee $employee
    ): RedirectResponse {

    /*
    |--------------------------------------------------------------------------
    | LOAD RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    $employee->load('user');


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([

        /*
        |--------------------------------------------------------------------------
        | PERSONAL
        |--------------------------------------------------------------------------
        */

        'nik' => [
            'required',
            'string',
            'max:50',
            Rule::unique(
                'employees',
                'nik'
            )->ignore($employee->id),
        ],

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique(
                'users',
                'email'
            )->ignore($employee->user_id),
        ],

        'phone' => [
            'nullable',
            'string',
            'max:30',
        ],

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        'password' => [
            'nullable',
            'string',
            'min:8',
            'confirmed',
        ],

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        'role' => [
            'required',
            Rule::in([
                'Employee',
                'Supervisor',
                'Manager',
                'Director',
            ]),
        ],

        /*
        |--------------------------------------------------------------------------
        | ORGANIZATION
        |--------------------------------------------------------------------------
        */

        'department_id' => [
            'required',
            'integer',
            'exists:departments,id',
        ],

        'position_id' => [
            'required',
            'integer',
            'exists:positions,id',
        ],

        /*
        |--------------------------------------------------------------------------
        | HIERARCHY
        |--------------------------------------------------------------------------
        */

        'supervisor_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
            'not_in:' . $employee->id,
        ],

        'manager_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
            'not_in:' . $employee->id,
        ],

        'director_id' => [
            'nullable',
            'integer',
            'exists:employees,id',
            'not_in:' . $employee->id,
        ],

        /*
        |--------------------------------------------------------------------------
        | PERSONAL DETAIL
        |--------------------------------------------------------------------------
        */

        'join_date' => [
            'required',
            'date',
        ],

        'birth_date' => [
            'nullable',
            'date',
            'before:today',
        ],

        'gender' => [
            'required',
            Rule::in([
                'Male',
                'Female',
            ]),
        ],

        'address' => [
            'nullable',
            'string',
            'max:1000',
        ],

        'status' => [
            'required',
            Rule::in([
                'Active',
                'Inactive',
            ]),
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | HIERARCHY VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $validated['role'] === 'Employee'
        && empty($validated['supervisor_id'])
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Employee wajib memiliki Supervisor.'
            );
    }


    if (
        $validated['role'] === 'Supervisor'
        && empty($validated['manager_id'])
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Supervisor wajib memiliki Manager.'
            );
    }


    if (
        $validated['role'] === 'Manager'
        && empty($validated['director_id'])
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'Manager wajib memiliki Director.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | CHECK USER
        |--------------------------------------------------------------------------
        */

        if (!$employee->user) {

            throw new \RuntimeException(
                'User yang terkait dengan employee tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK ROLE
        |--------------------------------------------------------------------------
        */

        $roleExists = \Spatie\Permission\Models\Role::query()
            ->where('name', $validated['role'])
            ->where('guard_name', 'web')
            ->exists();


        if (!$roleExists) {

            throw new \RuntimeException(
                'Role ' . $validated['role'] .
                ' belum tersedia pada database.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $userData = [

            'name' =>
                trim($validated['name']),

            'email' =>
                trim($validated['email']),

            'phone' =>
                $validated['phone'] ?? null,
        ];


        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        |
        | Password hanya diubah apabila diisi.
        |
        */

        if (!empty($validated['password'])) {

            $userData['password'] =
                Hash::make(
                    $validated['password']
                );
        }


        $employee->user->update(
            $userData
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE ROLE
        |--------------------------------------------------------------------------
        */

        $employee->user->syncRoles([
            $validated['role'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | HIERARCHY NORMALIZATION
        |--------------------------------------------------------------------------
        |
        | Relasi disesuaikan dengan role.
        |
        */

        $supervisorId = null;
        $managerId = null;
        $directorId = null;


        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        if ($validated['role'] === 'Employee') {

            $supervisorId =
                !empty($validated['supervisor_id'])
                    ? (int) $validated['supervisor_id']
                    : null;

            $managerId =
                !empty($validated['manager_id'])
                    ? (int) $validated['manager_id']
                    : null;

            $directorId =
                !empty($validated['director_id'])
                    ? (int) $validated['director_id']
                    : null;
        }


        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        elseif ($validated['role'] === 'Supervisor') {

            $managerId =
                !empty($validated['manager_id'])
                    ? (int) $validated['manager_id']
                    : null;
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        elseif ($validated['role'] === 'Manager') {

            $directorId =
                !empty($validated['director_id'])
                    ? (int) $validated['director_id']
                    : null;
        }


        /*
        |--------------------------------------------------------------------------
        | DIRECTOR
        |--------------------------------------------------------------------------
        |
        | Director berada pada level paling atas.
        |
        */

        elseif ($validated['role'] === 'Director') {

            $supervisorId = null;
            $managerId = null;
            $directorId = null;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE EMPLOYEE
        |--------------------------------------------------------------------------
        */

        $employee->update([

            'nik' =>
                trim($validated['nik']),

            'department_id' =>
                (int) $validated['department_id'],

            'position_id' =>
                (int) $validated['position_id'],

            'supervisor_id' =>
                $supervisorId,

            'manager_id' =>
                $managerId,

            'director_id' =>
                $directorId,

            'join_date' =>
                $validated['join_date'],

            'birth_date' =>
                !empty($validated['birth_date'])
                    ? $validated['birth_date']
                    : null,

            'gender' =>
                $validated['gender'],

            'address' =>
                $validated['address'] ?? null,

            'status' =>
                $validated['status'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        DB::commit();


        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Employee berhasil diperbarui.',
            [
                'employee_id' =>
                    $employee->id,

                'user_id' =>
                    $employee->user_id,

                'nik' =>
                    $employee->nik,

                'role' =>
                    $validated['role'],

                'supervisor_id' =>
                    $employee->supervisor_id,

                'manager_id' =>
                    $employee->manager_id,

                'director_id' =>
                    $employee->director_id,

                'updated_by' =>
                    auth()->id(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'hrd.employees.show',
                $employee
            )
            ->with(
                'success',
                'Data employee berhasil diperbarui.'
            );


    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::rollBack();


        /*
        |--------------------------------------------------------------------------
        | LOG ERROR
        |--------------------------------------------------------------------------
        */

        Log::error(
            'Update employee failed',
            [
                'employee_id' =>
                    $employee->id,

                'message' =>
                    $e->getMessage(),

                'exception' =>
                    get_class($e),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine(),

                'trace' =>
                    $e->getTraceAsString(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | ERROR RESPONSE
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput()
            ->with(
                'error',
                'Gagal memperbarui employee: ' .
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
        Employee $employee
    ): RedirectResponse {

        $employee->load([
            'user',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | CHECK SUBORDINATE
            |--------------------------------------------------------------------------
            */

            $hasSubordinates = Employee::query()
                ->where(function ($query) use ($employee) {

                    $query
                        ->where('manager_id', $employee->id)
                        ->orWhere('supervisor_id', $employee->id)
                        ->orWhere('director_id', $employee->id);

                })
                ->exists();


            if ($hasSubordinates) {

                throw new \Exception(
                    'Employee tidak dapat dihapus karena masih digunakan sebagai Supervisor, Manager, atau Direktur bagi employee lain.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | DELETE EMPLOYEE
            |--------------------------------------------------------------------------
            */

            $user = $employee->user;

            $employee->delete();


            /*
            |--------------------------------------------------------------------------
            | DELETE USER
            |--------------------------------------------------------------------------
            */

            if ($user) {

                $user->delete();
            }


            DB::commit();


            return redirect()
                ->route(
                    'hrd.employees.index'
                )
                ->with(
                    'success',
                    'Data employee berhasil dihapus.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    'Gagal menghapus employee: ' .
                    $e->getMessage()
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORT FORM
    |--------------------------------------------------------------------------
    */

    public function importForm(): View
    {
        return view(
            'hrd.employees.import'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IMPORT EMPLOYEE
    |--------------------------------------------------------------------------
    */

    public function import(
        Request $request
    ): RedirectResponse {

        $request->validate([

            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],

        ], [

            'file.required' =>
                'Silakan pilih file Excel atau CSV.',

            'file.file' =>
                'File yang dipilih tidak valid.',

            'file.mimes' =>
                'File harus berformat XLSX, XLS, atau CSV.',

            'file.max' =>
                'Ukuran file maksimal 5 MB.',
        ]);


        try {

            Excel::import(
                new EmployeeImport(),
                $request->file('file')
            );


            return redirect()
                ->route(
                    'hrd.employees.index'
                )
                ->with(
                    'success',
                    'Data employee berhasil diimport.'
                );

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                )
                ->withInput();

        } catch (\Throwable $e) {

            Log::error(
                'Employee import failed',
                [
                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );


            return back()
                ->with(
                    'error',
                    'Import gagal. Pastikan format file dan seluruh data sudah sesuai template.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function downloadTemplate()
    {
        return Excel::download(
            new EmployeeTemplateExport(),
            'template_employee.xlsx'
        );
    }
}