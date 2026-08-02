<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'card_brand',
        'cardholder_name',
        'card_number',
        'expiry_month',
        'expiry_year',
        'cvv',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maskedNumber()
    {
        if (!$this->card_number) {
            return null;
        }

        return '•••• •••• •••• ' . substr($this->card_number, -4);
    }

    public function expiryLabel()
    {
        if (!$this->expiry_month) {
            return null;
        }

        return $this->expiry_month . '/' . $this->expiry_year;
    }
}
