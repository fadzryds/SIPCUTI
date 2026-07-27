<?php

namespace App\Filament\Resources\LeaveApprovals\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeaveApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('leave_request_id')
                    ->relationship('leaveRequest', 'request_number')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('approver_id')
                    ->relationship('approver', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('approval_order')
                    ->options([
                        1 => 'Manager',
                        2 => 'HRD',
                    ])
                    ->required(),

                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->required(),

                Textarea::make('notes')
                    ->rows(4),

                DateTimePicker::make('approved_at'),

                FileUpload::make('signature_path')
                    ->directory('approval-signatures')
                    ->disk('public')
                    ->downloadable()
                    ->openable(),

            ]);
    }
}