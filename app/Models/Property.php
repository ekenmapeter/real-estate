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
        'title',
        'location',
        'category',
        'image_url',
        'price',
        'price_per_share',
        'total_shares',
        'available_shares',
        'roi_percentage',
        'investment_duration_months',
        'description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_share' => 'decimal:2',
        'roi_percentage' => 'decimal:2',
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

    public function galleryUrls(): array
    {
        $urls = collect($this->images->map(fn ($img) => $img->url()));
        if ($this->image_url) {
            $urls->prepend($this->image_url);
        }

        return $urls->values()->all();
    }

    public function purchasePrice(): float
    {
        return (float) ($this->price ?? ($this->price_per_share * $this->total_shares));
    }
}
