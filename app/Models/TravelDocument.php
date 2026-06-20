<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'document_type', 'document_number',
        'issuing_country', 'issue_date', 'expiry_date',
        'file_path', 'file_name', 'file_type', 'file_size',
        'notes', 'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days && !$this->isExpired();
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'passport' => 'Paspor',
            'ktp' => 'KTP',
            'visa' => 'Visa',
            'vaccination' => 'Sertifikat Vaksin',
            'insurance' => 'Asuransi Perjalanan',
            'ticket' => 'Tiket Pesawat',
            'hotel' => 'Voucher Hotel',
            'other' => 'Lainnya',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }
}
