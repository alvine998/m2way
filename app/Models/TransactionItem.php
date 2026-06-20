<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'customer_id', 'package_id', 'room_type', 'price', 'notes',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function transaction(): BelongsTo { return $this->belongsTo(Transaction::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function package(): BelongsTo { return $this->belongsTo(Package::class); }
}
