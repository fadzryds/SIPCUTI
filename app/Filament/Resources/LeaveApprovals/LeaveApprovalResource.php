<?php

namespace App\Filament\Resources\LeaveApprovals;

use App\Filament\Resources\LeaveApprovals\Pages\CreateLeaveApproval;
use App\Filament\Resources\LeaveApprovals\Pages\EditLeaveApproval;
use App\Filament\Resources\LeaveApprovals\Pages\ListLeaveApprovals;
use App\Filament\Resources\LeaveApprovals\Pages\ViewLeaveApproval;
use App\Filament\Resources\LeaveApprovals\Schemas\LeaveApprovalForm;
use App\Filament\Resources\LeaveApprovals\Schemas\LeaveApprovalInfolist;
use App\Filament\Resources\LeaveApprovals\Tables\LeaveApprovalsTable;
use App\Models\LeaveApproval;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LeaveApprovalResource extends Resource
{
    protected static ?string $model = LeaveApproval::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static string|\UnitEnum|null $navigationGroup = 'Leave Management';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'Leave Approvals';

    protected static ?string $recordTitleAttribute = 'status';

    public static function form(Schema $schema): Schema
    {
        return LeaveApprovalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveApprovalsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeaveApprovalInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeaveApprovals::route('/'),
            'create' => CreateLeaveApproval::route('/create'),
            'view' => ViewLeaveApproval::route('/{record}'),
            'edit' => EditLeaveApproval::route('/{record}/edit'),
        ];
    }
}