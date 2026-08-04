<?php

namespace App\Http\Controllers;

use App\Models\CreditSwap;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $walletBalance = $user ? $user->wallet_balance : 0.00;

        $creditSwaps = CreditSwap::with(['seller', 'buyer', 'responder'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOffers = $creditSwaps->where('status', 'active');

        $offersJson = $activeOffers->values()->map(fn ($s) => [
            'country' => $s->country ?? '',
            'amount' => (float) $s->amount,
            'payment' => $s->payment_method ?? '',
            'type' => $s->offer_type,
        ]);

        $mySwaps = $user
            ? $creditSwaps->filter(fn ($s) => $s->user_id === $user->id
                || $s->buyer_id === $user->id
                || $s->seller_id === $user->id)
                ->values()
            : collect();

        return view('marketplace', compact('creditSwaps', 'activeOffers', 'offersJson', 'mySwaps', 'user', 'walletBalance'));
    }
}