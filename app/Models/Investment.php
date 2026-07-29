<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'shares_bought',
        'total_amount',
        'expected_roi_amount',
        'roi_earned',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expected_roi_amount' => 'decimal:2',
        'roi_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
