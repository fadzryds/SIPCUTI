<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HrdDepartmentController extends Controller
{
    /**
     * Menampilkan daftar department.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY DEPARTMENT
        |--------------------------------------------------------------------------
        */

        $query = Department::query()
            ->withCount('employees');


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->has('status') && $request->status !== '') {

            $query->where(
                'is_active',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $departments = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalDepartments = Department::count();

        $activeDepartments = Department::where(
            'is_active',
            true
        )->count();

        $inactiveDepartments = Department::where(
            'is_active',
            false
        )->count();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.departments.index',
            compact(
                'departments',
                'totalDepartments',
                'activeDepartments',
                'inactiveDepartments'
            )
        );
    }


    /**
     * Menampilkan form tambah department.
     */
    public function create(): View
    {
        return view(
            'hrd.departments.create'
        );
    }


    /**
     * Menyimpan department baru.
     */
    public function store(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:departments,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Department::create($validated);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hrd.departments.index')
            ->with(
                'success',
                'Department berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan detail department.
     */
    public function show(Department $department): View
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD EMPLOYEES
        |--------------------------------------------------------------------------
        */

        $department->load([
            'employees.position',
            'employees.manager',
        ]);


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.departments.show',
            compact('department')
        );
    }


    /**
     * Menampilkan form edit department.
     */
    public function edit(Department $department): View
    {
        return view(
            'hrd.departments.edit',
            compact('department')
        );
    }


    /**
     * Mengupdate department.
     */
    public function update(
        Request $request,
        Department $department
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:departments,code,' . $department->id,
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $department->update($validated);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hrd.departments.index')
            ->with(
                'success',
                'Department berhasil diperbarui.'
            );
    }


    /**
     * Menghapus department.
     */
    public function destroy(
        Department $department
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | CEK EMPLOYEE
        |--------------------------------------------------------------------------
        |
        | Department yang masih memiliki employee
        | jangan langsung dihapus.
        |
        */

        if ($department->employees()->exists()) {

            return redirect()
                ->route('hrd.departments.index')
                ->with(
                    'error',
                    'Department tidak dapat dihapus karena masih memiliki karyawan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $department->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hrd.departments.index')
            ->with(
                'success',
                'Department berhasil dihapus.'
            );
    }
}