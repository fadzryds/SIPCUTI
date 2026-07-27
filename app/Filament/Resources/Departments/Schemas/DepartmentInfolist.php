<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DepartmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextEntry::make('code'),

            TextEntry::make('name'),

            TextEntry::make('description'),

            IconEntry::make('is_active')
                ->boolean(),

            TextEntry::make('created_at')
                ->dateTime(),

            TextEntry::make('updated_at')
                ->dateTime(),

        ]);
    }
}