<?php

namespace App\Filament\Resources\LeaveApprovals\Pages;

use App\Filament\Resources\LeaveApprovals\LeaveApprovalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveApproval extends EditRecord
{
    protected static string $resource = LeaveApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
