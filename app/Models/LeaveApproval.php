<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApproval extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Approval Level
    |--------------------------------------------------------------------------
    */

    public const LEVEL_EMPLOYEE = 'Employee';
    public const LEVEL_SUPERVISOR = 'Supervisor';
    public const LEVEL_MANAGER = 'Manager';
    public const LEVEL_DIRECTOR = 'Director';

    /*
    |--------------------------------------------------------------------------
    | Approval Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_WAITING = 'Waiting';
    public const STATUS_PENDING = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'leave_request_id',
        'approver_id',
        'approval_level',
        'status',
        'notes',
        'approved_at',
        'signature_path',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Request
    |--------------------------------------------------------------------------
    */

    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(
            LeaveRequest::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approver
    |--------------------------------------------------------------------------
    |
    | approver_id menggunakan users.id
    |
    */

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approver_id'
        );
    }
}