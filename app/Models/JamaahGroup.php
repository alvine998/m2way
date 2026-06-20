<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JamaahGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_number', 'name', 'package_id', 'leader_id',
        'departure_date', 'return_date', 'quota', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'quota' => 'integer',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (JamaahGroup $group) {
            if (empty($group->group_number)) {
                $group->group_number = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'GR-' . date('ymd') . '-';
        $last = static::where('group_number', 'like', $prefix . '%')
            ->orderByDesc('id')->value('group_number');
        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'leader_id');
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'jamaah_group_customer')
            ->withPivot('room_type', 'notes')
            ->withTimestamps();
    }

    public function memberCount(): int
    {
        return $this->customers()->count();
    }

    public function availableSlots(): int
    {
        return $this->quota - $this->memberCount();
    }
}
