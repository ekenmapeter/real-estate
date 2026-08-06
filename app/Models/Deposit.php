<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_code',
        'deposit_type',
        'payment_method',
        'payment_channel_id',
        'amount',
        'deposit_amount',
        'deposit_currency',
        'base_usd_value',
        'avc_rate',
        'gross_avc',
        'fee_amount',
        'net_avc',
        'rate_locked_at',
        'country',
        'currency',
        'sender_account_name',
        'sender_bank_name',
        'sender_account_number',
        'sender_email',
        'crypto_asset',
        'crypto_network',
        'tx_hash',
        'sender_wallet_address',
        'card_last_four',
        'card_brand',
        'card_exp_month',
        'card_exp_year',
        'processor_token',
        'processor_session_id',
        'details',
        'admin_instructions',
        'receipt_proof',
        'user_notes',
        'admin_notes',
        'internal_notes',
        'expires_at',
        'credited_at',
        'credited_by',
        'reference_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'base_usd_value' => 'decimal:2',
        'avc_rate' => 'decimal:4',
        'gross_avc' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_avc' => 'decimal:2',
        'admin_instructions' => 'array',
        'expires_at' => 'datetime',
        'rate_locked_at' => 'datetime',
        'credited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentChannel()
    {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id');
    }

    public function ledger()
    {
        return $this->hasOne(WalletLedger::class, 'deposit_id');
    }

    public function creditedByUser()
    {
        return $this->belongsTo(User::class, 'credited_by');
    }

    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }
        if ($this->expires_at && now()->greaterThan($this->expires_at) && in_array($this->status, ['payment_instructions_assigned', 'awaiting_payment'])) {
            return true;
        }
        return false;
    }

    public function canSubmitProof(): bool
    {
        return in_array($this->status, [
            'payment_instructions_assigned',
            'awaiting_payment',
            'additional_info_required',
        ]) && !$this->isExpired();
    }

    public function isCredited(): bool
    {
        return $this->status === 'avc_credited' || $this->credited_at !== null;
    }

    public function formattedStatusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'awaiting_finance_review' => 'Awaiting Finance Review',
            'payment_instructions_assigned' => 'Payment Instructions Assigned',
            'awaiting_payment' => 'Awaiting Payment',
            'payment_submitted' => 'Payment Submitted',
            'under_verification' => 'Under Verification',
            'additional_info_required' => 'Additional Info Required',
            'confirmed' => 'Confirmed',
            'avc_credited' => 'AVC Credited',
            'rejected' => 'Rejected',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'avc_credited', 'confirmed' => 'bg-success',
            'payment_submitted', 'under_verification' => 'bg-info text-dark',
            'payment_instructions_assigned', 'awaiting_payment' => 'bg-primary',
            'awaiting_finance_review', 'submitted' => 'bg-warning text-dark',
            'additional_info_required' => 'bg-warning text-dark',
            'rejected', 'expired', 'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function methodLabel(): string
    {
        return match ($this->payment_method) {
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit / Debit Card',
            'wire_transfer' => 'International Wire Transfer',
            'crypto' => 'Cryptocurrency',
            default => ucfirst(str_replace('_', ' ', $this->payment_method)),
        };
    }
}
