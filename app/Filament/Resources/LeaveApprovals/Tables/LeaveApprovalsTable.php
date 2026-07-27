<?php

namespace App\Filament\Resources\LeaveApprovals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaveApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('approved_at', 'desc')

            ->columns([

                TextColumn::make('leaveRequest.request_number')
                    ->label('Request No')
                    ->searchable(),

                TextColumn::make('approver.name')
                    ->label('Approver')
                    ->searchable(),

                TextColumn::make('approval_order')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Manager',
                        2 => 'HRD',
                        default => '-',
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('approved_at')
                    ->dateTime('d M Y H:i'),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}