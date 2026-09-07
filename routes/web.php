<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Controllers\Karyawan\LeaveController;
use App\Http\Controllers\Karyawan\ProfileController;

use App\Http\Controllers\Supervisor\SupervisorApprovalController;
use App\Http\Controllers\Supervisor\SupervisorLeaveController;
use App\Http\Controllers\Supervisor\SupervisorDashboardController;

use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerApprovalController;
use App\Http\Controllers\Manager\ManagerHistoryController;
use App\Http\Controllers\Manager\ManagerProfileController;

use App\Http\Controllers\Director\DirectorDashboardController;
use App\Http\Controllers\Director\DirectorApprovalController;

use App\Http\Controllers\HRD\HrdDashboardController;
use App\Http\Controllers\HRD\HrdEmployeeController;
use App\Http\Controllers\HRD\HrdDepartmentController;
use App\Http\Controllers\HRD\HrdHistoryController;
use App\Http\Controllers\HRD\HrdProfileController;
use App\Http\Controllers\HRD\HrdPositionController;
use App\Http\Controllers\HRD\HrdLeaveRequestController;
use App\Http\Controllers\HRD\HrdLeaveReportController;
use App\Http\Controllers\HRD\HrdLeaveBalanceController;


Route::get('/', function () {

    return redirect()->route('employee.dashboard');

});

/*
|--------------------------------------------------------------------------
| EMPLOYEE / KARYAWAN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Employee',])
        ->prefix('employee')
        ->name('employee.')
        ->group(function () {

    Route::get(
        '/dashboard', [DashboardController::class, 'index']
    )
    ->name('dashboard');

    Route::resource('leave',
        LeaveController::class
    );

    Route::get('/leave/{leave}/download',
        [LeaveController::class, 'downloadPdf']
    )
    ->name('leave.download');

    Route::get('/profile',
        [ProfileController::class, 'index']
    )
    ->name('profile');

    Route::put(
        '/profile/address',
        [ProfileController::class, 'updateAddress']
    )
    ->name('profile.address.update');

});

        /*
|--------------------------------------------------------------------------
| SUPERVISOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [SupervisorDashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | LEAVE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/leave',
            [SupervisorLeaveController::class, 'index']
        )->name('leave.index');

        Route::get(
            '/leave/{leave}',
            [SupervisorLeaveController::class, 'show']
        )->name('leave.show');

        Route::post(
            '/leave/{leave}/approve',
            [SupervisorLeaveController::class, 'approve']
        )->name('leave.approve');

        Route::post(
            '/leave/{leave}/reject',
            [SupervisorLeaveController::class, 'reject']
        )->name('leave.reject');
    });

/*
|--------------------------------------------------------------------------
| Manager
|--------------------------------------------------------------------------
*/

    Route::middleware(['auth','role:Manager',
        ])
            ->prefix('manager')
            ->name('manager.')
            ->group(function () {

    Route::get(
        '/dashboard',[ManagerDashboardController::class, 'index']
    )
    ->name('dashboard');

    Route::get(
        '/approval',[ManagerApprovalController::class, 'index']
    )
    ->name('approval.index');

    Route::get(
        '/approval/{leave}', [ManagerApprovalController::class, 'show']
    )
    ->name('approval.show');


    Route::post(
        '/approval/{leave}/process',[ManagerApprovalController::class, 'process']
    )
    ->name('approval.process');

    Route::get(
        '/history', [ManagerHistoryController::class, 'index']
    )
    ->name('history.index');


    Route::get(
        '/history/{leave}', [ManagerHistoryController::class, 'show']
    )
    ->name('history.show');

    Route::get(
        '/profile',[ManagerProfileController::class, 'index']
    )
    ->name('profile');

});

/*
|--------------------------------------------------------------------------
| DIRECTOR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Director'])
    ->prefix('director')
    ->name('director.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DirectorDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Approval
        |--------------------------------------------------------------------------
        */

         Route::get(
            '/approval',
            [DirectorApprovalController::class, 'index']
        )->name('approval.index');

        Route::get(
            '/approval/{leave}',
            [DirectorApprovalController::class, 'show']
        )->name('approval.show');

        Route::post(
            '/approval/{leave}/process',
            [DirectorApprovalController::class, 'process']
        )->name('approval.process');

    });
        /*
|--------------------------------------------------------------------------
| HRD
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:HRD',
])
    ->prefix('hrd')
    ->name('hrd.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [HrdDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'departments',
            HrdDepartmentController::class
        );

        /*
        |--------------------------------------------------------------------------
        | POSITIONS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'positions',
            HrdPositionController::class
        );

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE IMPORT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/employees/import',
            [HrdEmployeeController::class, 'importForm']
        )->name('employees.import.form');

        Route::post(
            '/employees/import',
            [HrdEmployeeController::class, 'import']
        )->name('employees.import');

        Route::get(
            '/employees/import/template',
            [HrdEmployeeController::class, 'downloadTemplate']
        )->name('employees.import.template');


        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE CRUD
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'employees',
            HrdEmployeeController::class
        );

        /*
        |--------------------------------------------------------------------------
        | LEAVE REQUEST MONITORING
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/leave-requests',
            [HrdLeaveRequestController::class, 'index']
        )->name('leave-requests.index');

        Route::get(
            '/leave-requests/{leave}',
            [HrdLeaveRequestController::class, 'show']
        )->name('leave-requests.show');

        Route::get(
            '/leave-requests/{leave}/download',
            [HrdLeaveRequestController::class, 'downloadPdf']
        )->name('leave-requests.download');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN CUTI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports/leave',
            [HrdLeaveReportController::class, 'index']
        )->name('reports.leave');

        Route::get(
            '/reports/leave/export',
            [HrdLeaveReportController::class, 'export']
        )->name('reports.leave.export');

        /*
        |--------------------------------------------------------------------------
        | PROFILE HRD
        |--------------------------------------------------------------------------
        */
        
        Route::get(
            '/hrdprofile',
            [HrdProfileController::class, 'index']
        )->name('profile');
        
        Route::put(
            '/hrdprofile',
            [HrdProfileController::class, 'update']
        )->name('profile.update');
        
        Route::put(
            '/hrdprofile/password',
            [HrdProfileController::class, 'updatePassword']
        )->name('profile.password');

        /*
        |--------------------------------------------------------------------------
        | LEAVE BALANCE
        |--------------------------------------------------------------------------
        */
        
        Route::get(
            '/leave-balances',
            [HrdLeaveBalanceController::class, 'index']
        )->name('leave-balances.index');
        
        Route::get(
            '/leave-balances/{leaveBalance}/edit',
            [HrdLeaveBalanceController::class, 'edit']
        )->name('leave-balances.edit');
        
        Route::put(
            '/leave-balances/{leaveBalance}',
            [HrdLeaveBalanceController::class, 'update']
        )->name('leave-balances.update');

    });
    
require __DIR__.'/auth.php';