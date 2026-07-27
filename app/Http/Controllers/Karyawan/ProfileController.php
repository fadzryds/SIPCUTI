<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Employee::with([
            'user',
            'department',
            'position',
            'manager.user',
        ])->where('user_id', Auth::id())
          ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Statistik Cuti
        |--------------------------------------------------------------------------
        */

        $remainingLeave = LeaveBalance::where('employee_id', $employee->id)
            ->sum('remaining');

        $pending = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_PENDING)
            ->count();

        $approved = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_APPROVED)
            ->count();

        $rejected = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', LeaveRequest::STATUS_REJECTED)
            ->count();

        return view('karyawan.profile', compact(
            'employee',
            'remainingLeave',
            'pending',
            'approved',
            'rejected'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Address
    |--------------------------------------------------------------------------
    */

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:1000',
        ]);

        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $employee->update([
            'address' => $request->address,
        ]);

        return redirect()
            ->route('employee.profile')
            ->with('success', 'Alamat berhasil diperbarui.');
    }
}