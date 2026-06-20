<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_number', 'customer_id', 'package_id', 'user_id',
        'departure_date', 'total_price', 'discount', 'grand_total',
        'status', 'payment_status', 'payment_type', 'payment_method',
        'refund_reason', 'refunded_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'total_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'refunded_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Transaction $transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $prefix = 'M2W-' . date('ymd') . '-';
        $last = static::where('transaction_number', 'like', $prefix . '%')
            ->orderByDesc('id')->value('transaction_number');
        $sequence = $last ? (int) substr($last, -4) + 1 : 1;
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function items(): HasMany { return $this->hasMany(TransactionItem::class); }

    public function paidAmount(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function remainingAmount(): float
    {
        return (float) $this->grand_total - $this->paidAmount();
    }

    public function recalculatePaymentStatus(): void
    {
        $paid = $this->paidAmount();
        if ($paid <= 0) $status = 'unpaid';
        elseif ($paid >= (float) $this->grand_total) $status = 'paid';
        else $status = 'partial';
        $this->update(['payment_status' => $status]);
    }

    public function scopeBelumLunas(Builder $query): Builder
    {
        return $query->where('payment_status', '!=', 'paid')
                     ->where('status', '!=', 'refund');
    }

    public function scopeLunas(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid')
                     ->where('status', '!=', 'refund');
    }

    public function scopeRefund(Builder $query): Builder
    {
        return $query->where('status', 'refund');
    }

    public function getPaymentTypeLabelAttribute(): string
    {
        return match($this->payment_type) {
            'cicilan' => 'Cicilan',
            'cash' => 'Cash',
            default => ucfirst($this->payment_type ?? '-'),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
            'ewallet' => 'E-Wallet',
            default => ucfirst(str_replace('_', ' ', $this->payment_method ?? '-')),
        };
    }
}
