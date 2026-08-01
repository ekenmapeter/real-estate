<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'location',
        'category',
        'image_url',
        'target_amount',
        'minimum_investment',
        'expected_return_percentage',
        'investment_duration_months',
        'description',
        'document_path',
        'status',
        'rating',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'minimum_investment' => 'decimal:2',
        'expected_return_percentage' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($project) {
            if (empty($project->uuid)) {
                $project->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function investments()
    {
        return $this->hasMany(ProjectInvestment::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedProject::class);
    }

    public function raisedAmount(): float
    {
        return (float) $this->investments()
            ->where('status', 'active')
            ->sum('amount');
    }

    public function fundedPercent(): int
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return (int) round(($this->raisedAmount() / $this->target_amount) * 100);
    }

    public function endsAt(): ?\Carbon\Carbon
    {
        if (!$this->created_at || $this->investment_duration_months <= 0) {
            return null;
        }

        return $this->created_at->copy()->addMonths($this->investment_duration_months);
    }

    public function countdownSeconds(): int
    {
        $endsAt = $this->endsAt();
        if (!$endsAt) {
            return 0;
        }

        return max(0, $endsAt->timestamp - now()->timestamp);
    }

    public function isActiveWindow(): bool
    {
        return $this->status === 'active' && $this->countdownSeconds() > 0;
    }

    public function ratingWidth(): int
    {
        return (int) round(min(5, max(0, (float) $this->rating)) / 5 * 100);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Ongoing',
            'completed' => 'Completed',
            'closed' => 'Closed',
            default => ucfirst($this->status),
        };
    }
}
