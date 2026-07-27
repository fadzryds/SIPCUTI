<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('user.name')
                    ->label('Employee'),

                TextEntry::make('nik'),

                TextEntry::make('department.name'),

                TextEntry::make('position.name'),

                TextEntry::make('manager.user.name')
                    ->label('Manager'),

                TextEntry::make('join_date')
                    ->date(),

                TextEntry::make('birth_date')
                    ->date(),

                TextEntry::make('gender'),

                TextEntry::make('status'),

                TextEntry::make('address'),

            ]);
    }
}