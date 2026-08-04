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
        'seller_id',
        'offer_type',
        'amount',
        'payment_method',
        'country',
        'payment_details',
        'admin_note',
        'notes',
        'status',
        'reference',
        'listing_number',
        'logs',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'logs' => 'array',
    ];

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

    public function listingLabel(): string
    {
        return $this->listing_number ?: ($this->reference ? '#' . substr($this->reference, 6) : '#' . $this->id);
    }

    public function inDeal(): bool
    {
        return $this->status === 'in_deal';
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The user whose credits are locked in escrow for this swap.
     * - Sell offer: the poster (user_id).
     * - Buy offer: the seller who responded (seller_id), or null before a seller responds.
     */
    public function escrowHolder()
    {
        return $this->offer_type === 'buy' ? $this->responder : $this->seller;
    }

    /**
     * The user who receives credits when the swap completes.
     * - Sell offer: the buyer (buyer_id).
     * - Buy offer: the poster (user_id).
     */
    public function creditBuyer()
    {
        return $this->offer_type === 'buy' ? $this->seller : $this->buyer;
    }

    public function offerTypeLabel(): string
    {
        return $this->offer_type === 'buy' ? 'Buy Offer' : 'Sell Offer';
    }
}
