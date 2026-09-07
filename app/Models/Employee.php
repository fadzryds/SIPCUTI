<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = 'Active';
    public const STATUS_INACTIVE = 'Inactive';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'nik',
        'department_id',
        'position_id',

        // Struktur organisasi
        'director_id',
        'manager_id',
        'supervisor_id',

        'join_date',
        'birth_date',
        'gender',
        'address',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'join_date' => 'date',
            'birth_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Position
    |--------------------------------------------------------------------------
    */

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /*
    |--------------------------------------------------------------------------
    | DIRECTOR
    |--------------------------------------------------------------------------
    |
    | Employee ini berada di bawah Director tertentu.
    |
    */

    public function director(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'director_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGER
    |--------------------------------------------------------------------------
    |
    | Employee ini berada di bawah Manager tertentu.
    |
    */

    public function manager(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'manager_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR
    |--------------------------------------------------------------------------
    |
    | Employee ini berada di bawah Supervisor tertentu.
    |
    */

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'supervisor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DIRECTOR → MANAGERS
    |--------------------------------------------------------------------------
    */

    public function managers(): HasMany
    {
        return $this->hasMany(
            Employee::class,
            'director_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MANAGER → SUPERVISORS
    |--------------------------------------------------------------------------
    */

    public function supervisors(): HasMany
    {
        return $this->hasMany(
            Employee::class,
            'manager_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPERVISOR → EMPLOYEES
    |--------------------------------------------------------------------------
    */

    public function subordinates(): HasMany
    {
        return $this->hasMany(
            Employee::class,
            'supervisor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LEAVE
    |--------------------------------------------------------------------------
    */

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(
            LeaveBalance::class
        );
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(
            LeaveRequest::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return $this->user?->name ?? '-';
    }

    public function getDepartmentNameAttribute(): string
    {
        return $this->department?->name ?? '-';
    }

    public function getPositionNameAttribute(): string
    {
        return $this->position?->name ?? '-';
    }

    public function getManagerNameAttribute(): string
    {
        return $this->manager?->user?->name ?? '-';
    }

    public function getSupervisorNameAttribute(): string
    {
        return $this->supervisor?->user?->name ?? '-';
    }

    public function getDirectorNameAttribute(): string
    {
        return $this->director?->user?->name ?? '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }
}