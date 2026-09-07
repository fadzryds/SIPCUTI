<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HrdPositionController extends Controller
{
    /**
     * Display a listing of positions.
     */
    public function index(Request $request)
    {
        $query = Position::query()
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
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'is_active',
                $request->status === '1'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $positions = $query
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalPositions = Position::count();

        $activePositions = Position::where(
            'is_active',
            true
        )->count();

        $inactivePositions = Position::where(
            'is_active',
            false
        )->count();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.positions.index',
            compact(
                'positions',
                'totalPositions',
                'activePositions',
                'inactivePositions'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'hrd.positions.create'
        );
    }


    /**
     * Store a newly created position.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:positions,code',
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
            ],
            [
                'code.required' =>
                    'Kode position wajib diisi.',

                'code.unique' =>
                    'Kode position sudah digunakan.',

                'code.max' =>
                    'Kode position maksimal 50 karakter.',

                'name.required' =>
                    'Nama position wajib diisi.',

                'name.max' =>
                    'Nama position maksimal 255 karakter.',

                'is_active.required' =>
                    'Status position wajib dipilih.',

                'is_active.boolean' =>
                    'Status position tidak valid.',
            ]
        );

        Position::create($validated);

        return redirect()
            ->route('hrd.positions.index')
            ->with(
                'success',
                'Position berhasil ditambahkan.'
            );
    }


    /**
     * Display the specified position.
     */
    public function show(Position $position)
    {
        /*
        |--------------------------------------------------------------------------
        | LOAD EMPLOYEE COUNT
        |--------------------------------------------------------------------------
        */

        $position->loadCount('employees');

        /*
        |--------------------------------------------------------------------------
        | LOAD EMPLOYEES
        |--------------------------------------------------------------------------
        |
        | Employee tidak memiliki kolom "name".
        | Nama karyawan berasal dari relasi User.
        |
        */

        $position->load([
            'employees' => function ($query) {

                $query->with([
                    'user',
                    'department',
                ]);
            },
        ]);

        /*
        |--------------------------------------------------------------------------
        | SORT EMPLOYEES BY USER NAME
        |--------------------------------------------------------------------------
        |
        | Tidak menggunakan:
        |
        | ->orderBy('name')
        |
        | karena kolom name tidak terdapat pada employees.
        |
        */

        $position->setRelation(
            'employees',
            $position->employees
                ->sortBy(function ($employee) {

                    return strtolower(
                        $employee->user?->name ?? ''
                    );

                })
                ->values()
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'hrd.positions.show',
            compact('position')
        );
    }


    /**
     * Show edit form.
     */
    public function edit(Position $position)
    {
        return view(
            'hrd.positions.edit',
            compact('position')
        );
    }


    /**
     * Update the specified position.
     */
    public function update(
        Request $request,
        Position $position
    ) {

        $validated = $request->validate(
            [
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique(
                        'positions',
                        'code'
                    )->ignore($position->id),
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
            ],
            [
                'code.required' =>
                    'Kode position wajib diisi.',

                'code.unique' =>
                    'Kode position sudah digunakan.',

                'code.max' =>
                    'Kode position maksimal 50 karakter.',

                'name.required' =>
                    'Nama position wajib diisi.',

                'name.max' =>
                    'Nama position maksimal 255 karakter.',

                'is_active.required' =>
                    'Status position wajib dipilih.',

                'is_active.boolean' =>
                    'Status position tidak valid.',
            ]
        );

        $position->update($validated);

        return redirect()
            ->route(
                'hrd.positions.edit',
                $position
            )
            ->with(
                'success',
                'Position berhasil diperbarui.'
            );
    }


    /**
     * Remove the specified position.
     */
    public function destroy(Position $position)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | CHECK EMPLOYEE USAGE
            |--------------------------------------------------------------------------
            */

            if ($position->employees()->exists()) {

                return redirect()
                    ->route('hrd.positions.index')
                    ->with(
                        'error',
                        'Position tidak dapat dihapus karena masih digunakan oleh data karyawan.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            $position->delete();

            return redirect()
                ->route('hrd.positions.index')
                ->with(
                    'success',
                    'Position berhasil dihapus.'
                );

        } catch (QueryException $e) {

            return redirect()
                ->route('hrd.positions.index')
                ->with(
                    'error',
                    'Position tidak dapat dihapus karena masih digunakan oleh data karyawan.'
                );
        }
    }
}