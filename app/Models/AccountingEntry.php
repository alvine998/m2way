<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entry_number', 'type', 'category', 'description', 'amount',
        'date', 'reference_type', 'reference_id', 'user_id', 'attachments', 'notes',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'date' => 'date', 'attachments' => 'array'];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (AccountingEntry $entry) {
            if (empty($entry->entry_number)) {
                $entry->entry_number = self::generateNumber($entry->type);
            }
        });
    }

    public static function generateNumber(string $type): string
    {
        $prefix = $type === 'income' ? 'INC-' : 'EXP-';
        $prefix .= date('ymd') . '-';
        $last = static::where('entry_number', 'like', $prefix . '%')
            ->orderByDesc('id')->value('entry_number');
        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function reference(): MorphTo { return $this->morphTo(); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
