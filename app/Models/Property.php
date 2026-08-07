<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'location',
        'country',
        'state',
        'city',
        'address',
        'category',
        'listing_type',
        'image_url',
        'price',
        'monthly_rent',
        'security_deposit',
        'price_per_share',
        'total_shares',
        'available_shares',
        'roi_percentage',
        'investment_duration_months',
        'description',
        'bedrooms',
        'bathrooms',
        'property_size',
        'land_size',
        'parking',
        'amenities_json',
        'ownership_type',
        'video_url',
        'is_verified',
        'is_featured',
        'views_count',
        'representative_role',
        'representative_verified',
        'admin_note',
        'logs',
        'listed_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'price_per_share' => 'decimal:2',
        'roi_percentage' => 'decimal:2',
        'property_size' => 'decimal:2',
        'land_size' => 'decimal:2',
        'amenities_json' => 'array',
        'logs' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'representative_verified' => 'boolean',
        'listed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($property) {
            if (empty($property->uuid)) {
                $property->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function appendLog(string $action, ?string $actor = null): void
    {
        $logs = $this->logs ?? [];
        $logs[] = [
            'at' => now()->toDateTimeString(),
            'actor' => $actor ?? 'System',
            'action' => $action,
        ];
        $this->logs = $logs;
    }

    public function ref(): string
    {
        return $this->listing_number ?: 'AVP-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function isForSale(): bool
    {
        return $this->listing_type === 'sale';
    }

    public function isForRent(): bool
    {
        return $this->listing_type === 'rent';
    }

    public function displayPrice(): ?float
    {
        return $this->isForRent() ? (float) $this->monthly_rent : $this->purchasePrice();
    }

    public function purchasePrice(): float
    {
        return (float) ($this->price ?? ($this->price_per_share ? $this->price_per_share * $this->total_shares : 0));
    }

    public function amenities(): array
    {
        return is_array($this->amenities_json) ? $this->amenities_json : [];
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'more_info_required' => 'More Info Required',
            'approved' => 'Approved',
            'published' => 'Published',
            'suspended' => 'Suspended',
            'rejected' => 'Rejected',
            'sold' => 'Sold',
            'rented' => 'Rented',
            'expired' => 'Expired',
            'archived' => 'Archived',
            default => ucfirst((string) $this->status),
        };
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function representativeLabel(): string
    {
        return match ($this->representative_role) {
            'owner' => 'Owner',
            'agent' => 'Real Estate Agent',
            'developer' => 'Developer',
            'property_manager' => 'Property Manager',
            default => 'Aurevia',
        };
    }

    public function fullLocation(): string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);
        return $parts ? implode(', ', $parts) : $this->location;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedProperty::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function inquiries()
    {
        return $this->hasMany(PropertyInquiry::class);
    }

    public function conversations()
    {
        return $this->hasMany(PropertyConversation::class);
    }

    public function galleryUrls(): array
    {
        $urls = collect($this->images->map(fn ($img) => $img->url()));
        if ($this->image_url) {
            $urls->prepend($this->image_url);
        }

        return $urls->values()->all();
    }
}
