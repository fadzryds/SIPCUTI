<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Hidden::make('request_number')
                    ->default(fn () => 'CUTI-' . now()->format('YmdHis')),

                Hidden::make('status')
                    ->default('Pending'),

                Hidden::make('submitted_at')
                    ->default(now()),

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

                DatePicker::make('start_date')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                
                        if (!$get('end_date')) {
                            return;
                        }
                
                        $days = Carbon::parse($state)
                            ->diffInDays(Carbon::parse($get('end_date'))) + 1;
                
                        $set('total_days', $days);
                    }),
                
                DatePicker::make('end_date')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                
                        if (!$get('start_date')) {
                            return;
                        }
                
                        $days = Carbon::parse($get('start_date'))
                            ->diffInDays(Carbon::parse($state)) + 1;
                
                        $set('total_days', $days);
                }),

                TextInput::make('total_days')
                    ->numeric()
                    ->readOnly(),

                Textarea::make('reason')
                    ->rows(4)
                    ->required(),

                FileUpload::make('attachment')
                    ->directory('leave-attachments')
                    ->downloadable()
                    ->openable(),

            ]);
    }
}