<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Position Information')
                    ->schema([

                        TextEntry::make('code')
                            ->label('Position Code'),

                        TextEntry::make('name')
                            ->label('Position Name'),

                        TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('-'),

                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean(),

                    ])
                    ->columns(2),

                Section::make('System Information')
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d M Y H:i'),

                    ])
                    ->columns(2),

            ]);
    }
}