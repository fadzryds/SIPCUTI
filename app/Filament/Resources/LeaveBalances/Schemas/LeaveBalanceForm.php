<?php

namespace App\Filament\Resources\LeaveBalances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('employee_id')
                    ->relationship('employee.user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('year')
                    ->numeric()
                    ->default(date('Y'))
                    ->required(),

                TextInput::make('quota')
                    ->numeric()
                    ->live()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('remaining', (int)$state - (int)$get('used'));
                    }),

                TextInput::make('used')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('remaining', (int)$get('quota') - (int)$state);
                    }),

                TextInput::make('remaining')
                    ->numeric()
                    ->readOnly(),

            ]);
    }
}