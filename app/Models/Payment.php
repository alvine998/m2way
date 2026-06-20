<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number', 'transaction_id', 'amount', 'payment_method',
        'payment_date', 'proof', 'notes', 'user_id',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'payment_date' => 'date'];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Payment $payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generateNumber();
            }
        });
        static::created(function (Payment $payment) {
            $payment->transaction->recalculatePaymentStatus();
        });
        static::deleted(function (Payment $payment) {
            $payment->transaction->recalculatePaymentStatus();
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'PAY-' . date('ymd') . '-';
        $last = static::where('payment_number', 'like', $prefix . '%')
            ->orderByDesc('id')->value('payment_number');
        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function transaction(): BelongsTo { return $this->belongsTo(Transaction::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
