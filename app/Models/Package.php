<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'type', 'description', 'duration', 'departure_date', 'return_date',
        'departure_city', 'quota', 'quota_remaining', 'base_price', 'hpp', 'cost_details',
        'airline', 'hotel_name', 'hotel_star', 'includes', 'excludes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'base_price' => 'decimal:2',
            'hpp' => 'decimal:2',
            'cost_details' => 'array',
            'includes' => 'array',
            'excludes' => 'array',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function calculateHpp(): void
    {
        $total = collect($this->cost_details ?? [])->sum('amount');
        $this->update(['hpp' => $total]);
    }

    public function profit(): float
    {
        return (float) $this->base_price - (float) $this->hpp;
    }
}
