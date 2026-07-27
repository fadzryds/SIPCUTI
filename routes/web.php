<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Controllers\Karyawan\LeaveController;
use App\Http\Controllers\Karyawan\ProfileController;

use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerApprovalController;
use App\Http\Controllers\Manager\ManagerHistoryController;
use App\Http\Controllers\Manager\ManagerProfileController;

use App\Http\Controllers\HRD\HrdDashboardController;
use App\Http\Controllers\HRD\HrdApprovalController;
use App\Http\Controllers\HRD\HrdHistoryController;
use App\Http\Controllers\HRD\HrdProfileController;

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('employee.dashboard');
});

/*
|--------------------------------------------------------------------------
| Employee
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Employee'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('leave', LeaveController::class);

        Route::view('/create', 'karyawan.leave.create')
            ->name('create');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::put('/profile/address', [ProfileController::class, 'updateAddress'])
            ->name('profile.address.update');
            
         /*
        |--------------------------------------------------------------------------
        | Download PDF Surat Cuti
        |--------------------------------------------------------------------------
        */

        Route::get('leave/{leave}/download', [LeaveController::class, 'downloadPdf']) 
            ->name('leave.download');

    });

/*
|--------------------------------------------------------------------------
| Manager
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Approval
        |--------------------------------------------------------------------------
        */

        Route::get('/approval', [ManagerApprovalController::class, 'index'])
            ->name('approval.index');

        Route::get('/approval/{leave}', [ManagerApprovalController::class, 'show'])
            ->name('approval.show');

        Route::post('/approval/{leave}/approve', [ManagerApprovalController::class, 'approve'])
            ->name('approval.approve');

        Route::post('/approval/{leave}/reject', [ManagerApprovalController::class, 'reject'])
            ->name('approval.reject');

        Route::post('/approval/{leave}/process', [ManagerApprovalController::class, 'process'])
            ->name('approval.process');

        /*
        |--------------------------------------------------------------------------
        | History
        |--------------------------------------------------------------------------
        */

        Route::get('/history', [ManagerHistoryController::class, 'index'])
            ->name('history.index');

        Route::get('/history/{leave}', [ManagerHistoryController::class, 'show'])
            ->name('history.show');

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [ManagerProfileController::class, 'index'])
            ->name('profile');

    });

/*
|--------------------------------------------------------------------------
| HRD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:HRD'])
    ->prefix('hrd')
    ->name('hrd.')
    ->group(function () {

        Route::get('/dashboard', [HrdDashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Approval
        |--------------------------------------------------------------------------
        */

        Route::get('/approval', [HrdApprovalController::class, 'index'])
            ->name('approval.index');

        Route::get('/approval/{leave}', [HrdApprovalController::class, 'show'])
            ->name('approval.show');

        Route::post('/approval/{leave}/process', [HrdApprovalController::class, 'process'])
            ->name('approval.process');

        /*
        |--------------------------------------------------------------------------
        | History
        |--------------------------------------------------------------------------
        */

        Route::get('/history', [HrdHistoryController::class, 'index'])
            ->name('history.index');

        Route::get('/history/{leave}', [HrdHistoryController::class, 'show'])
            ->name('history.show');

        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [HrdProfileController::class, 'index'])
            ->name('profile');

        Route::put('/profile', [HrdProfileController::class, 'update'])
            ->name('profile.update');

    });

require __DIR__.'/auth.php';