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
        'price_per_share',
        'total_shares',
        'available_shares',
        'roi_percentage',
        'investment_duration_months',
        'description',
        'status',
    ];

    protected $casts = [
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
}
