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
    ])
    ->where('user_id', Auth::id())
    ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | TAHUN BERJALAN
    |--------------------------------------------------------------------------
    */

    $currentYear = now()->year;

    /*
    |--------------------------------------------------------------------------
    | TOTAL HAK CUTI
    |--------------------------------------------------------------------------
    |
    | Total hak cuti diambil dari QUOTA.
    | Bukan dari remaining.
    |
    */

    $totalLeave = LeaveBalance::query()
        ->where('employee_id', $employee->id)
        ->where('year', $currentYear)
        ->where('is_active', true)
        ->sum('quota');

    /*
    |--------------------------------------------------------------------------
    | CUTI YANG SUDAH DIGUNAKAN
    |--------------------------------------------------------------------------
    |
    | Yang mengurangi hak cuti adalah jumlah hari dari
    | pengajuan yang sudah APPROVED.
    |
    */

    $usedLeave = LeaveRequest::query()
        ->where('employee_id', $employee->id)
        ->where('status', LeaveRequest::STATUS_APPROVED)
        ->whereYear('start_date', $currentYear)
        ->sum('total_days');

    /*
    |--------------------------------------------------------------------------
    | SISA CUTI
    |--------------------------------------------------------------------------
    |
    | Sisa = Total Hak Cuti - Total Hari Cuti Approved
    |
    */

    $remainingLeave = max(
        (int) $totalLeave - (int) $usedLeave,
        0
    );

    /*
    |--------------------------------------------------------------------------
    | STATISTIK PENGAJUAN
    |--------------------------------------------------------------------------
    */

    // Jumlah pengajuan yang masih menunggu approval
    $pending = LeaveRequest::query()
        ->where('employee_id', $employee->id)
        ->where('status', LeaveRequest::STATUS_PENDING)
        ->count();

    // Jumlah pengajuan yang sudah disetujui
    $approved = LeaveRequest::query()
        ->where('employee_id', $employee->id)
        ->where('status', LeaveRequest::STATUS_APPROVED)
        ->count();

    // Jumlah pengajuan yang ditolak
    $rejected = LeaveRequest::query()
        ->where('employee_id', $employee->id)
        ->where('status', LeaveRequest::STATUS_REJECTED)
        ->count();

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'karyawan.profile',
        compact(
            'employee',
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