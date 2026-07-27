<?php

namespace App\Filament\Resources\LeaveTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('code')
                    ->label('Leave Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),

                TextInput::make('name')
                    ->label('Leave Name')
                    ->required()
                    ->maxLength(100),

                TextInput::make('max_days')
                    ->label('Maximum Days')
                    ->numeric()
                    ->required(),

                Toggle::make('requires_attachment')
                    ->label('Requires Attachment')
                    ->default(false),

                Toggle::make('is_paid')
                    ->label('Paid Leave')
                    ->default(true),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

            ]);
    }
}