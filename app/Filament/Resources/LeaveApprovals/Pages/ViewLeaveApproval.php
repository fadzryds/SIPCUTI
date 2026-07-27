<?php

namespace App\Filament\Resources\LeaveApprovals\Pages;

use App\Filament\Resources\LeaveApprovals\LeaveApprovalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeaveApproval extends ViewRecord
{
    protected static string $resource = LeaveApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
