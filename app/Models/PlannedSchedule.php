<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlannedSchedule extends Model
{
    protected $fillable = [
        'package_id',
        'planned_date',
        'target_pax',
        'booked_pax',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_date' => 'date',
            'target_pax' => 'integer',
            'booked_pax' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->whereMonth('planned_date', $month)
                     ->whereYear('planned_date', $year);
    }
}