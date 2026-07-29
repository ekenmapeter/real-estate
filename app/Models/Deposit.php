<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_code',
        'amount',
        'payment_method',
        'country',
        'currency',
        'sender_account_name',
        'sender_account_number',
        'sender_email',
        'details',
        'receipt_proof',
        'user_notes',
        'admin_instructions',
        'expires_at',
        'reference_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_instructions' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
