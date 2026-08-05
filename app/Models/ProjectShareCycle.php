<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectShareCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_code',
        'user_id',
        'project_id',
        'duration_key',
        'duration_label',
        'duration_days',
        'shares_owned',
        'required_shares',
        'share_price',
        'total_purchase_amount',
        'target_earnings_pct',
        'projected_earnings',
        'completion_value',
        'status',
        'purchased_at',
        'activated_at',
        'completion_date',
        'earnings_credited_at',
        'receipt_number',
    ];

    protected $casts = [
        'shares_owned' => 'integer',
        'required_shares' => 'integer',
        'share_price' => 'decimal:2',
        'total_purchase_amount' => 'decimal:2',
        'target_earnings_pct' => 'decimal:2',
        'projected_earnings' => 'decimal:2',
        'completion_value' => 'decimal:2',
        'purchased_at' => 'datetime',
        'activated_at' => 'datetime',
        'completion_date' => 'datetime',
        'earnings_credited_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($cycle) {
            if (empty($cycle->cycle_code)) {
                $cycle->cycle_code = 'CYC-' . strtoupper(Str::random(10));
            }
            if (empty($cycle->receipt_number)) {
                $cycle->receipt_number = 'RCP-' . date('Ymd') . '-' . rand(1000, 9999);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function isPendingActivation(): bool
    {
        return $this->status === 'pending_activation';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function remainingSharesNeeded(): int
    {
        return max(0, $this->required_shares - $this->shares_owned);
    }

    public function activationProgressPercent(): int
    {
        if ($this->required_shares <= 0) return 100;
        return (int) min(100, round(($this->shares_owned / $this->required_shares) * 100));
    }

    public function cycleDaysPassed(): int
    {
        if (!$this->activated_at) return 0;
        return (int) min($this->duration_days, now()->diffInDays($this->activated_at));
    }

    public function cycleDaysRemaining(): int
    {
        if (!$this->activated_at || !$this->completion_date) return $this->duration_days;
        if (now()->greaterThanOrEqualTo($this->completion_date)) return 0;
        return (int) max(0, now()->diffInDays($this->completion_date, false));
    }

    public function cycleProgressPercent(): int
    {
        if (!$this->activated_at || $this->duration_days <= 0) return 0;
        $passed = $this->cycleDaysPassed();
        return (int) min(100, round(($passed / $this->duration_days) * 100));
    }
}
