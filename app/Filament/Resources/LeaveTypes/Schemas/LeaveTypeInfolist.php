<?php

namespace App\Filament\Resources\LeaveTypes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeaveTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('code')
                    ->label('Code'),

                TextEntry::make('name')
                    ->label('Leave Type'),

                TextEntry::make('max_days')
                    ->label('Maximum Days'),

                IconEntry::make('requires_attachment')
                    ->label('Requires Attachment')
                    ->boolean(),

                IconEntry::make('is_paid')
                    ->label('Paid Leave')
                    ->boolean(),

                IconEntry::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i'),

                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y H:i'),

            ]);
    }
}