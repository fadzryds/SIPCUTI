<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrdProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $employee = Employee::with([
            'user',
            'department',
            'position',
        ])
        ->where('user_id', Auth::id())
        ->firstOrFail();

        $pending = LeaveApproval::where('approval_level', LeaveApproval::LEVEL_HRD)
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_PENDING)
            ->count();

        $approved = LeaveApproval::where('approval_level', LeaveApproval::LEVEL_HRD)
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_APPROVED)
            ->count();

        $rejected = LeaveApproval::where('approval_level', LeaveApproval::LEVEL_HRD)
            ->where('approver_id', Auth::id())
            ->where('status', LeaveApproval::STATUS_REJECTED)
            ->count();

        $totalApproval = LeaveApproval::where('approval_level', LeaveApproval::LEVEL_HRD)
            ->where('approver_id', Auth::id())
            ->count();

        return view('hrd.profile.index', compact(
            'employee',
            'pending',
            'approved',
            'rejected',
            'totalApproval'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Address
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $request->validate([
            'address' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $employee = Employee::where('user_id', Auth::id())
            ->firstOrFail();

        $employee->update([
            'address' => $request->address,
        ]);

        return back()->with(
            'success',
            'Alamat berhasil diperbarui.'
        );
    }
}