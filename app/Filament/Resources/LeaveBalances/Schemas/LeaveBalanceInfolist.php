<?php

namespace App\Filament\Resources\LeaveBalances\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeaveBalanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('employee.user.name')
                    ->label('Employee'),

                TextEntry::make('leaveType.name')
                    ->label('Leave Type'),

                TextEntry::make('year'),

                TextEntry::make('quota'),

                TextEntry::make('used'),

                TextEntry::make('remaining'),

                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),

            ]);
    }
}