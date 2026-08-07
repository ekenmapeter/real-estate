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
        'preferred_currency',
        'expires_at',
        'kyc_verified',
        'kyc_document_path',
        'kyc_selfie_path',
        'kyc_status',
        'kyc_submitted_at',
        'kyc_rejected_reason',
        'referred_by',
        'rep_type',
        'rep_status',
        'rep_verified_at',
        'rep_documents',
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function repLabel(): string
    {
        return match ($this->rep_type) {
            'owner' => 'Owner',
            'agent' => 'Real Estate Agent',
            'developer' => 'Developer',
            'property_manager' => 'Property Manager',
            default => 'Member',
        };
    }

    public function isRepresentativeVerified(): bool
    {
        return $this->rep_status === 'verified';
    }

    public function propertyListings()
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    public function propertyInquiries()
    {
        return $this->hasMany(PropertyInquiry::class, 'user_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

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
            'rep_verified_at' => 'datetime',
            'rep_documents' => 'array',
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

    public function walletLedgers()
    {
        return $this->hasMany(WalletLedger::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function savedWithdrawalMethods()
    {
        return $this->hasMany(SavedWithdrawalMethod::class);
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

    public function shareCycles()
    {
        return $this->hasMany(ProjectShareCycle::class);
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
