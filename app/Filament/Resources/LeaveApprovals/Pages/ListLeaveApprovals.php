<?php

namespace App\Filament\Resources\LeaveApprovals\Pages;

use App\Filament\Resources\LeaveApprovals\LeaveApprovalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveApprovals extends ListRecords
{
    protected static string $resource = LeaveApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
