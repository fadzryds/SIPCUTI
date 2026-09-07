<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveBalance;
use App\Models\Employee;

class CarryForwardLeave extends Command
{
    protected $signature = 'leave:carry-forward';

    protected $description = 'Carry Forward Saldo Cuti Tahunan';

    public function handle()
    {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        $employees = Employee::all();

        foreach ($employees as $employee) {

            $oldBalance = LeaveBalance::where('employee_id', $employee->id)
                ->where('year', $previousYear)
                ->first();

            if (!$oldBalance) {
                continue;
            }

            $carryForward = $oldBalance->remaining;

            LeaveBalance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $oldBalance->leave_type_id,
                    'year' => $currentYear,
                ],
                [
                    'quota' => 12 + $carryForward,
                    'used' => 0,
                    'remaining' => 12 + $carryForward,
                    'carry_forward' => $carryForward,
                    'is_active' => true,
                ]
            );
        }

        $this->info('Carry Forward berhasil dijalankan.');
    }
}