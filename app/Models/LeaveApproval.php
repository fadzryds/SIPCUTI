<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Approval Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_WAITING   = 'Waiting';
    public const STATUS_PENDING   = 'Pending';
    public const STATUS_APPROVED  = 'Approved';
    public const STATUS_REJECTED  = 'Rejected';
    public const STATUS_CANCELLED = 'Cancelled';

    /*
    |--------------------------------------------------------------------------
    | Approval Level
    |--------------------------------------------------------------------------
    */

    public const LEVEL_MANAGER = 'Manager';
    public const LEVEL_HRD     = 'HRD';

    protected $fillable = [

        'leave_request_id',

        'approver_id',

        'approval_level',

        'status',

        'notes',

        'approved_at',

        'signature_path',

    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isWaiting(): bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isManagerApproval(): bool
    {
        return $this->approval_level === self::LEVEL_MANAGER;
    }

    public function isHrdApproval(): bool
    {
        return $this->approval_level === self::LEVEL_HRD;
    }
}