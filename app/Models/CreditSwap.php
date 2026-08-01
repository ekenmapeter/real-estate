<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditSwap extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_id',
        'amount',
        'payment_method',
        'payment_details',
        'status',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
