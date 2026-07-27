<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('code')
                    ->label('Position Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),

                TextInput::make('name')
                    ->label('Position Name')
                    ->required()
                    ->maxLength(100),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(4),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

            ]);
    }
}