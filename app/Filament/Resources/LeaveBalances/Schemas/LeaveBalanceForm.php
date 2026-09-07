<?php

namespace App\Filament\Resources\LeaveBalances\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | EMPLOYEE
                |--------------------------------------------------------------------------
                |
                | PENTING:
                | employee_id harus mengambil employees.id,
                | bukan users.id.
                |
                */

                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship(
                        name: 'employee',
                        titleAttribute: 'id'
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (Employee $record): string =>
                            $record->user?->name
                            ?? $record->nik
                            ?? 'Karyawan #' . $record->id
                    )
                    ->searchable([
                        'nik',
                    ])
                    ->preload()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | LEAVE TYPE
                |--------------------------------------------------------------------------
                */

                Select::make('leave_type_id')
                    ->label('Jenis Cuti')
                    ->relationship(
                        name: 'leaveType',
                        titleAttribute: 'name'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | YEAR
                |--------------------------------------------------------------------------
                */

                TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->default(now()->year)
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | QUOTA
                |--------------------------------------------------------------------------
                */

                TextInput::make('quota')
                    ->label('Kuota')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->live()
                    ->required()
                    ->afterStateUpdated(
                        function (
                            $state,
                            callable $set,
                            callable $get
                        ) {

                            $quota = (int) $state;
                            $used = (int) $get('used');

                            $set(
                                'remaining',
                                max($quota - $used, 0)
                            );
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | USED
                |--------------------------------------------------------------------------
                */

                TextInput::make('used')
                    ->label('Terpakai')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->live()
                    ->required()
                    ->afterStateUpdated(
                        function (
                            $state,
                            callable $set,
                            callable $get
                        ) {

                            $quota = (int) $get('quota');
                            $used = (int) $state;

                            $set(
                                'remaining',
                                max($quota - $used, 0)
                            );
                        }
                    ),

                /*
                |--------------------------------------------------------------------------
                | REMAINING
                |--------------------------------------------------------------------------
                */

                TextInput::make('remaining')
                    ->label('Sisa')
                    ->numeric()
                    ->default(0)
                    ->readOnly()
                    ->dehydrated(true),

                /*
                |--------------------------------------------------------------------------
                | CARRY FORWARD
                |--------------------------------------------------------------------------
                */

                TextInput::make('carry_forward')
                    ->label('Carry Forward')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                Select::make('is_active')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ])
                    ->default(1)
                    ->required(),

            ]);
    }
}