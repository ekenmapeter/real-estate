<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_key',
        'channel_name',
        'account_name',
        'bank_or_provider',
        'account_number',
        'country',
        'currency',
        'swift_bic',
        'iban',
        'wallet_asset',
        'blockchain_network',
        'wallet_address',
        'destination_tag_memo',
        'min_deposit_amount',
        'max_deposit_amount',
        'daily_limit',
        'current_capacity',
        'processing_info',
        'status',
        'visibility',
    ];

    protected $casts = [
        'min_deposit_amount' => 'decimal:2',
        'max_deposit_amount' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'current_capacity' => 'decimal:2',
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function methodLabel(): string
    {
        return match ($this->method_key) {
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit / Debit Card',
            'wire_transfer' => 'International Wire',
            'crypto' => 'Cryptocurrency',
            default => ucfirst(str_replace('_', ' ', $this->method_key)),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active' => 'bg-success',
            'maintenance' => 'bg-warning text-dark',
            'full_capacity' => 'bg-danger',
            'country_restricted', 'currency_restricted' => 'bg-secondary',
            default => 'bg-dark',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'maintenance' => 'Maintenance',
            'full_capacity' => 'Full Capacity',
            'country_restricted' => 'Country Restricted',
            'currency_restricted' => 'Currency Restricted',
            default => 'Inactive',
        };
    }
}
