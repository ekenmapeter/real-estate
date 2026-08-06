<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'direction',
        'category',
        'payment_method',
        'amount',
        'fiat_equivalent',
        'fee_amount',
        'reference',
        'description',
        'notes',
        'status',
        'receipt_proof',
        'related_type',
        'related_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fiat_equivalent' => 'decimal:2',
        'fee_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function isCredit(): bool
    {
        return $this->direction === 'credit' || in_array($this->type, ['deposit', 'credit', 'bonus', 'referral_bonus', 'cashback', 'roi', 'rental_income', 'refund', 'signup_bonus']);
    }

    public function isDebit(): bool
    {
        return $this->direction === 'debit' || in_array($this->type, ['withdrawal', 'debit', 'investment', 'property_purchase', 'fee', 'marketplace_sale', 'send_funds']);
    }

    public function signedAmount(): string
    {
        $prefix = $this->isCredit() ? '+' : '-';
        return $prefix . number_format($this->amount, 2);
    }

    public function formattedStatusLabel(): string
    {
        return match ($this->status) {
            'completed', 'approved', 'success' => 'Completed',
            'pending', 'submitted', 'processing', 'under_review', 'awaiting_payment' => 'Pending',
            'failed', 'rejected', 'declined' => 'Failed',
            'cancelled' => 'Cancelled',
            'escrow', 'locked', 'in_deal' => 'In Escrow',
            default => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'completed', 'approved', 'success' => 'bg-success bg-opacity-15 text-success',
            'pending', 'submitted', 'processing', 'under_review', 'awaiting_payment' => 'bg-warning bg-opacity-15 text-warning-emphasis',
            'failed', 'rejected', 'declined', 'cancelled' => 'bg-danger bg-opacity-15 text-danger',
            'escrow', 'locked', 'in_deal' => 'bg-info bg-opacity-15 text-info',
            default => 'bg-secondary bg-opacity-15 text-secondary',
        };
    }

    public function categoryIcon(): string
    {
        return match ($this->category) {
            'deposit' => 'bi-arrow-down-left-circle-fill text-success',
            'withdrawal' => 'bi-arrow-up-right-circle-fill text-danger',
            'marketplace' => 'bi-shop text-primary',
            'escrow' => 'bi-shield-lock-fill text-warning',
            'investment' => 'bi-building text-info',
            'earnings' => 'bi-graph-up-arrow text-success',
            'fees' => 'bi-receipt text-secondary',
            'referral' => 'bi-people-fill text-purple',
            'adjustment' => 'bi-sliders text-dark',
            default => 'bi-arrow-down-up text-primary',
        };
    }

    // Scopes
    public function scopeCredits($query)
    {
        return $query->where('direction', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('direction', 'debit');
    }

    public function scopeCategory($query, $cat)
    {
        if ($cat && $cat !== 'all') {
            return $query->where('category', $cat);
        }
        return $query;
    }
}
