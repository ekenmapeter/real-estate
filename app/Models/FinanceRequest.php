<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'user_id',
        'type',
        'country',
        'currency',
        'amount',
        'payment_method',
        'sender_name',
        'sender_account',
        'sender_email',
        'user_notes',
        'status',
        'assigned_payment_method',
        'assigned_account_name',
        'assigned_account_number',
        'assigned_reference',
        'assigned_instructions',
        'expires_at',
        'payment_evidence',
        'evidence_notes',
        'evidence_submitted_at',
        'admin_notes',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'evidence_submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return Carbon::now()->greaterThan($this->expires_at);
    }

    public function remainingSeconds(): int
    {
        if (!$this->expires_at) {
            return 0;
        }
        $diff = Carbon::now()->diffInSeconds($this->expires_at, false);
        return max(0, (int) $diff);
    }

    public function formattedStatusLabel(): string
    {
        return match ($this->status) {
            'under_review' => 'Under Review',
            'payment_instructions_assigned' => 'Payment Instructions Available',
            'evidence_submitted', 'under_verification' => 'Payment Submitted',
            'completed' => 'Approved / Completed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'under_review' => 'bg-warning bg-opacity-15 text-warning-emphasis',
            'payment_instructions_assigned' => 'bg-info bg-opacity-15 text-info-emphasis fw-bold',
            'evidence_submitted', 'under_verification' => 'bg-primary bg-opacity-15 text-primary',
            'completed' => 'bg-success bg-opacity-15 text-success',
            'rejected', 'cancelled' => 'bg-danger bg-opacity-15 text-danger',
            default => 'bg-secondary bg-opacity-15 text-secondary',
        };
    }
}
