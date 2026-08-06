<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedWithdrawalMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method_key',
        'title',
        'account_name',
        'bank_or_provider',
        'account_number',
        'masked_account_number',
        'account_type',
        'swift_bic',
        'iban',
        'routing_number',
        'bank_address',
        'country',
        'currency',
        'crypto_asset',
        'crypto_network',
        'wallet_address',
        'destination_tag_memo',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public static function maskNumber(?string $number): string
    {
        if (!$number) return '••••';
        $clean = preg_replace('/\s+/', '', $number);
        $len = strlen($clean);
        if ($len <= 4) return '•••• ' . $clean;
        return '•••• ' . substr($clean, -4);
    }

    public function methodIcon(): string
    {
        return match ($this->method_key) {
            'bank_transfer' => 'bi-bank text-primary',
            'mobile_wallet' => 'bi-phone text-info',
            'wire_transfer' => 'bi-globe text-indigo',
            'crypto' => 'bi-currency-bitcoin text-warning',
            default => 'bi-wallet2',
        };
    }
}
