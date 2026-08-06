<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'withdrawal_code',
        'withdrawal_type',
        'withdrawal_method',
        'saved_withdrawal_method_id',
        'amount',
        'avc_amount',
        'avc_rate',
        'gross_usd_value',
        'platform_fee',
        'processing_fee',
        'estimated_net_payout',
        'payout_currency',
        'country',
        'currency',
        'account_name',
        'bank_or_provider',
        'account_number',
        'account_type',
        'swift_bic',
        'iban',
        'routing_number',
        'bank_address',
        'crypto_asset',
        'crypto_network',
        'wallet_address',
        'destination_tag_memo',
        'account_details',
        'user_notes',
        'admin_notes',
        'transaction_reference',
        'receipt_proof',
        'status',
        'processed_at',
        'completed_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'avc_amount' => 'decimal:2',
        'avc_rate' => 'decimal:4',
        'gross_usd_value' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'estimated_net_payout' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savedMethod()
    {
        return $this->belongsTo(SavedWithdrawalMethod::class, 'saved_withdrawal_method_id');
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function isPending(): bool
    {
        return in_array($this->status, [
            'submitted',
            'security_verification',
            'finance_review',
            'approved',
            'processing',
            'payment_sent',
            'more_info_required',
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['submitted', 'security_verification', 'finance_review']);
    }

    public function methodLabel(): string
    {
        return match ($this->withdrawal_method) {
            'bank_transfer' => 'Local Bank Transfer',
            'mobile_wallet' => 'GCash / Mobile Wallet',
            'wire_transfer' => 'International Wire Transfer',
            'crypto' => 'Cryptocurrency',
            default => ucfirst(str_replace('_', ' ', $this->withdrawal_method)),
        };
    }

    public function formattedStatusLabel(): string
    {
        return match ($this->status) {
            'submitted' => 'Request Submitted',
            'security_verification' => 'Security Verification',
            'finance_review' => 'Finance Review',
            'approved' => 'Approved',
            'processing' => 'Processing Payout',
            'payment_sent' => 'Payment Sent',
            'completed' => 'Completed',
            'more_info_required' => 'More Info Required',
            'rejected' => 'Rejected',
            'failed' => 'Failed',
            'returned' => 'Returned',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'completed' => 'bg-success',
            'payment_sent', 'approved', 'processing' => 'bg-info text-dark',
            'finance_review', 'submitted', 'security_verification' => 'bg-warning text-dark',
            'more_info_required' => 'bg-warning text-dark',
            'rejected', 'failed', 'returned', 'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
