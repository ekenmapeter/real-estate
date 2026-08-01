<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'account_id',
        'wallet_balance',
        'role',
        'affiliate_code',
        'affiliate_earnings',
        'expires_at',
        'kyc_verified',
        'kyc_document_path',
        'kyc_selfie_path',
        'kyc_status',
        'kyc_submitted_at',
        'kyc_rejected_reason',
        'referred_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'affiliate_earnings' => 'decimal:2',
            'expires_at' => 'datetime',
            'kyc_submitted_at' => 'datetime',
            'kyc_verified' => 'boolean',
        ];
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function projectInvestments()
    {
        return $this->hasMany(ProjectInvestment::class);
    }

    public function savedProjects()
    {
        return $this->hasMany(SavedProject::class);
    }

    public function savedProperties()
    {
        return $this->hasMany(SavedProperty::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function creditSwaps()
    {
        return $this->hasMany(CreditSwap::class, 'user_id');
    }

    public function purchasedSwaps()
    {
        return $this->hasMany(CreditSwap::class, 'buyer_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }
}
