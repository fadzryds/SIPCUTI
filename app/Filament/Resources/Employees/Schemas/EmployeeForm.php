<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('user_id')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                
                TextInput::make('nik')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(30),

                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('position_id')
                    ->relationship('position', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('manager_id')
                    ->label('Manager')
                    ->options(function () {
                
                return Employee::whereHas('user.roles', function ($query) {
                    $query->where('name', 'Manager');
                    })
                        ->with('user')
                        ->get()
                        ->pluck('user.name', 'id');
                
                    })
                    ->searchable()
                    ->preload(),

                DatePicker::make('join_date')
                    ->required(),

                DatePicker::make('birth_date')
                    ->required(),

                Radio::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->inline()
                    ->required(),

                Select::make('status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ])
                    ->default('Active')
                    ->required(),

                Textarea::make('address')
                    ->rows(3)
                    ->columnSpanFull(),

            ]);
    }
}