<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'year',
        'quota',
        'used',
        'remaining',
        'carry_forward',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'quota' => 'integer',
            'used' => 'integer',
            'remaining' => 'integer',
            'carry_forward' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class
        );
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(
            LeaveType::class
        );
    }
}