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
        'property_type',
        'bedrooms',
        'bathrooms',
        'land_size_sqm',
        'building_size_sqm',
        'parking_spaces',
        'floors',
        'total_units',
        'amenities_json',
        'image_url',
        'target_amount',
        'share_price',
        'minimum_investment',
        'expected_return_percentage',
        'investment_duration_months',
        'funding_closing_date',
        'description',
        'developer_summary',
        'purpose',
        'revenue_source',
        'current_stage',
        'year_built',
        'condition',
        'document_path',
        'status',
        'rating',
        'is_verified',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'share_price' => 'decimal:2',
        'minimum_investment' => 'decimal:2',
        'expected_return_percentage' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'funding_closing_date' => 'datetime',
        'amenities_json' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($project) {
            if (empty($project->uuid)) {
                $project->uuid = (string) Str::uuid();
            }
            if (empty($project->share_price) || $project->share_price <= 0) {
                $project->share_price = 100.00;
            }
            if (empty($project->funding_closing_date) && $project->investment_duration_months > 0) {
                $project->funding_closing_date = now()->addMonths($project->investment_duration_months);
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

    public function durationTiers()
    {
        return $this->hasMany(ProjectDurationTier::class);
    }

    public function shareCycles()
    {
        return $this->hasMany(ProjectShareCycle::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function updates()
    {
        return $this->hasMany(ProjectUpdate::class)->orderBy('published_at', 'desc');
    }

    public function savedBy()
    {
        return $this->hasMany(SavedProject::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(ProjectReview::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get available duration tiers with fallback defaults.
     */
    public function getAvailableTiers()
    {
        $tiers = $this->durationTiers;
        if ($tiers && $tiers->count() > 0) {
            return $tiers;
        }

        // Return default tiers based on share price
        $price = (float) ($this->share_price ?: 100.00);

        return collect([
            (object) [
                'duration_key' => '14_days',
                'duration_label' => '14 Days',
                'duration_days' => 14,
                'required_shares' => 10,
                'min_avc_value' => $price * 10,
                'target_earnings_pct' => 4.00,
                'is_popular' => true,
            ],
            (object) [
                'duration_key' => '1_month',
                'duration_label' => '1 Month',
                'duration_days' => 30,
                'required_shares' => 25,
                'min_avc_value' => $price * 25,
                'target_earnings_pct' => 8.00,
                'is_popular' => false,
            ],
            (object) [
                'duration_key' => '3_months',
                'duration_label' => '3 Months',
                'duration_days' => 90,
                'required_shares' => 50,
                'min_avc_value' => $price * 50,
                'target_earnings_pct' => 16.00,
                'is_popular' => false,
            ],
        ]);
    }

    public function averageRating(): float
    {
        if ($this->relationLoaded('reviews') && $this->reviews->count() > 0) {
            return round($this->reviews->avg('rating'), 1);
        }

        $avg = $this->reviews()->avg('rating');
        if ($avg !== null && $avg > 0) {
            return round((float) $avg, 1);
        }

        return (float) ($this->rating ?: 4.5);
    }

    public function reviewCount(): int
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }

    public function galleryUrls(): array
    {
        $urls = collect($this->images->map(fn ($img) => $img->url()));
        if ($this->image_url) {
            $urls->prepend($this->image_url);
        }

        return $urls->values()->all();
    }

    public function raisedAmount(): float
    {
        $legacySum = (float) $this->investments()->where('status', 'active')->sum('amount');
        $cyclesSum = (float) $this->shareCycles()->whereIn('status', ['pending_activation', 'active', 'completed'])->sum('total_purchase_amount');
        return max($legacySum, $cyclesSum);
    }

    public function fundedPercent(): int
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return (int) round(($this->raisedAmount() / $this->target_amount) * 100);
    }

    public function uniqueShareholdersCount(): int
    {
        return $this->shareCycles()
            ->whereIn('status', ['pending_activation', 'active', 'completed'])
            ->distinct('user_id')
            ->count('user_id');
    }

    public function endsAt(): ?\Carbon\Carbon
    {
        if ($this->funding_closing_date) {
            return $this->funding_closing_date;
        }
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

    public function remainingDaysFormatted(): string
    {
        $sec = $this->countdownSeconds();
        if ($sec <= 0) return '0 Days';
        $days = floor($sec / 86400);
        $hours = floor(($sec % 86400) / 3600);
        return "{$days}d {$hours}h Remaining";
    }

    public function isActiveWindow(): bool
    {
        return $this->status === 'active' && ($this->countdownSeconds() > 0 || !$this->funding_closing_date);
    }

    public function ratingWidth(): int
    {
        return (int) round(min(5, max(0, $this->averageRating())) / 5 * 100);
    }

    public function ref(): string
    {
        return 'PRJ-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
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
