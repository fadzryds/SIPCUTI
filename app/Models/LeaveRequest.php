<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING   = 'Pending';
    public const STATUS_APPROVED  = 'Approved';
    public const STATUS_REJECTED  = 'Rejected';
    public const STATUS_CANCELLED = 'Cancelled';

    protected $fillable = [
        'request_number',
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'submitted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvals()
    {
        return $this->hasMany(LeaveApproval::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (LeaveRequest $request) {

            $year = now()->year;

            $lastNumber = self::whereYear('created_at', $year)->count() + 1;

            $request->request_number =
                'CT-' . $year . '-' . str_pad($lastNumber, 6, '0', STR_PAD_LEFT);

            $request->submitted_at = now();

            $request->status = self::STATUS_PENDING;
        });

        static::created(function (LeaveRequest $request) {

            $employee = Employee::with('manager.user')->find($request->employee_id);

            /*
            |--------------------------------------------------------------------------
            | Approval Manager
            |--------------------------------------------------------------------------
            */

            if ($employee && $employee->manager && $employee->manager->user_id) {

                LeaveApproval::create([
                    'leave_request_id' => $request->id,
                    'approver_id'      => $employee->manager->user_id,
                    'approval_level'   => LeaveApproval::LEVEL_MANAGER,
                    'status'           => LeaveApproval::STATUS_PENDING,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Approval HRD
            |--------------------------------------------------------------------------
            */

            $hrd = Employee::with('user')
                ->where('approval_level', 'HRD')
                ->first();

            if ($hrd && $hrd->user_id) {

                LeaveApproval::create([
                    'leave_request_id' => $request->id,
                    'approver_id'      => $hrd->user_id,
                    'approval_level'   => LeaveApproval::LEVEL_HRD,
                    'status'           => LeaveApproval::STATUS_WAITING,
                ]);
            }
        });
    }

    public function managerApproval()
    {
        return $this->hasOne(LeaveApproval::class)
            ->where('approval_level', LeaveApproval::LEVEL_MANAGER);
    }
    
    public function hrdApproval()
    {
        return $this->hasOne(LeaveApproval::class)
            ->where('approval_level', LeaveApproval::LEVEL_HRD);
    }
}