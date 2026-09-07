<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeaveRequest extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'Pending';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'request_number',
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'employee_signature_path',
        'status',
        'submitted_at',
        'current_approver_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'submitted_at' => 'datetime',
            'total_days' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Employee
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Type
    |--------------------------------------------------------------------------
    */

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(
            LeaveType::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approvals
    |--------------------------------------------------------------------------
    */

    public function approvals(): HasMany
    {
        return $this->hasMany(
            LeaveApproval::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Current Approver
    |--------------------------------------------------------------------------
    |
    | current_approver_id menyimpan USER ID
    | dari orang yang sedang harus melakukan approval.
    |
    */

    public function currentApprover(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'current_approver_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGER APPROVAL
    |--------------------------------------------------------------------------
    */

    public function managerApproval(): HasOne
    {
        return $this->hasOne(
            LeaveApproval::class,
            'leave_request_id'
        )->where(
            'approval_level',
            LeaveApproval::LEVEL_MANAGER
        );
    }

}