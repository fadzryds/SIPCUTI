<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeaveRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([

                TextEntry::make('request_number')
                    ->label('Request Number'),

                TextEntry::make('employee.user.name')
                    ->label('Employee'),

                TextEntry::make('employee.department.name')
                    ->label('Department'),

                TextEntry::make('employee.position.name')
                    ->label('Position'),

                TextEntry::make('leaveType.name')
                    ->label('Leave Type'),

                TextEntry::make('start_date')
                    ->date('d M Y'),

                TextEntry::make('end_date')
                    ->date('d M Y'),

                TextEntry::make('total_days')
                    ->label('Total Days'),

                TextEntry::make('reason'),

                TextEntry::make('status')
                    ->badge(),

                TextEntry::make('submitted_at')
                    ->dateTime('d M Y H:i'),

            ]);
    }
}