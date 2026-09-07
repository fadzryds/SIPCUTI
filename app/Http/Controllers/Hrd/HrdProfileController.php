<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HrdProfileController extends Controller
{
    /**
     * =========================================================
     * PROFILE
     * =========================================================
     */
    public function index()
    {
        $user = Auth::user();

        /*
         * Pastikan relasi employee tersedia.
         *
         * Struktur:
         * User
         *   └── Employee
         *       ├── Department
         *       └── Position
         */
        $user->load([
            'employee.department',
            'employee.position',
        ]);

        $employee = $user->employee;

        return view('hrd.hrdprofile.index', compact(
            'user',
            'employee'
        ));
    }


    /**
     * =========================================================
     * UPDATE PROFILE
     * =========================================================
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $employee = $user->employee;

        /*
         * -----------------------------------------------------
         * VALIDATION
         * -----------------------------------------------------
         */
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'birth_date' => [
                'nullable',
                'date',
            ],

            'gender' => [
                'nullable',
                Rule::in([
                    'L',
                    'P',
                ]),
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);


        /*
         * -----------------------------------------------------
         * UPDATE USER
         * -----------------------------------------------------
         */
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();


        /*
         * -----------------------------------------------------
         * UPDATE EMPLOYEE
         * -----------------------------------------------------
         */
        if ($employee) {

            $employee->birth_date =
                $validated['birth_date'] ?? null;

            $employee->gender =
                $validated['gender'] ?? null;

            $employee->address =
                $validated['address'] ?? null;

            $employee->save();
        }


        return redirect()
            ->route('hrd.profile')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }


    /**
     * =========================================================
     * UPDATE PASSWORD
     * =========================================================
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([

            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ], [

            'current_password.required' =>
                'Password saat ini wajib diisi.',

            'current_password.current_password' =>
                'Password saat ini tidak sesuai.',

            'password.required' =>
                'Password baru wajib diisi.',

            'password.min' =>
                'Password baru minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak cocok.',

        ]);


        /*
         * -----------------------------------------------------
         * UPDATE PASSWORD
         * -----------------------------------------------------
         */
        $user = Auth::user();

        $user->password =
            Hash::make(
                $validated['password']
            );

        $user->save();


        return redirect()
            ->route('hrd.profile')
            ->with(
                'password_success',
                'Password berhasil diperbarui.'
            );
    }
}