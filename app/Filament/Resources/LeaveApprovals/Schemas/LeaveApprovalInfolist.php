<?php

namespace App\Filament\Resources\LeaveApprovals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeaveApprovalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('leaveRequest.request_number')
                    ->label('Request Number'),

                TextEntry::make('approver.name')
                    ->label('Approver'),

                TextEntry::make('approval_order')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Manager',
                        2 => 'HRD',
                        default => '-',
                    }),

                TextEntry::make('status')
                    ->badge(),

                TextEntry::make('notes'),

                TextEntry::make('approved_at')
                    ->dateTime('d M Y H:i'),

            ]);
    }
}