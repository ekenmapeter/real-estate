<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class AvcMarketplaceController extends Controller
{
    protected array $listings = [
        ['reference' => 'AVC-8K01Z036', 'type' => 'sell', 'userName' => 'Sarah J.**', 'verified' => true, 'amount' => 500, 'country' => 'Philippines', 'countryFlag' => '🇵🇭', 'paymentMethod' => 'Bank Transfer', 'currency' => 'USD', 'escrowStatus' => 'Secured in Escrow', 'age' => '2 hours ago', 'status' => 'live', 'own' => false],
        ['reference' => 'AVC-J2M9B017', 'type' => 'buy', 'userName' => 'Daniel K.**', 'verified' => true, 'amount' => 1000, 'country' => 'United Arab Emirates', 'countryFlag' => '🇦🇪', 'paymentMethod' => 'USDT', 'currency' => 'USDT', 'escrowStatus' => 'Awaiting Seller', 'age' => '5 hours ago', 'status' => 'live', 'own' => false],
        ['reference' => 'AVC-K9J0P771', 'type' => 'sell', 'userName' => 'Maria G.**', 'verified' => true, 'amount' => 300, 'country' => 'Spain', 'countryFlag' => '🇪🇸', 'paymentMethod' => 'Bank Transfer', 'currency' => 'EUR', 'escrowStatus' => 'Secured in Escrow', 'age' => '8 hours ago', 'status' => 'live', 'own' => false],
        ['reference' => 'AVC-4R7T2Q19', 'type' => 'buy', 'userName' => 'Ahmed K.**', 'verified' => true, 'amount' => 750, 'country' => 'United Kingdom', 'countryFlag' => '🇬🇧', 'paymentMethod' => 'GCash', 'currency' => 'USD', 'escrowStatus' => 'Awaiting Seller', 'age' => '1 day ago', 'status' => 'live', 'own' => false],
        ['reference' => 'AVC-9W3X5Y22', 'type' => 'sell', 'userName' => 'Jessica L.**', 'verified' => true, 'amount' => 2000, 'country' => 'Singapore', 'countryFlag' => '🇸🇬', 'paymentMethod' => 'Cryptocurrency', 'currency' => 'USDT', 'escrowStatus' => 'Secured in Escrow', 'age' => '1 day ago', 'status' => 'live', 'own' => false],
        ['reference' => 'AVC-6B8N4M33', 'type' => 'sell', 'userName' => 'Robert B.**', 'verified' => false, 'amount' => 150, 'country' => 'Canada', 'countryFlag' => '🇨🇦', 'paymentMethod' => 'PayPal', 'currency' => 'CAD', 'escrowStatus' => 'Pending Review', 'age' => '2 days ago', 'status' => 'pending_review', 'own' => false],
        ['reference' => 'AVC-2D5F7H44', 'type' => 'sell', 'userName' => 'You', 'verified' => true, 'amount' => 500, 'country' => 'United States', 'countryFlag' => '🇺🇸', 'paymentMethod' => 'Bank Transfer', 'currency' => 'USD', 'escrowStatus' => 'Secured in Escrow', 'age' => '3 hours ago', 'status' => 'live', 'own' => true],
    ];

    protected array $deals = [
        [
            'reference' => 'AVCD-702913', 'listingReference' => 'AVC-8K01Z036',
            'role' => 'buyer', 'status' => 'awaiting_payment', 'statusLabel' => 'Buyer Payment Pending',
            'avcAmount' => 500, 'paymentMethod' => 'Bank Transfer', 'currency' => 'USD',
            'paymentAmount' => 500.00, 'escrowStatus' => 'Secured in Escrow',
            'counterparty' => 'Sarah J.**', 'currentStage' => 5, 'deadlineSeconds' => 1785,
            'paymentInstructions' => [
                ['label' => 'Account Holder', 'value' => 'Sarah J.***'],
                ['label' => 'Bank / Provider', 'value' => 'BDO Unibank'],
                ['label' => 'Account Number', 'value' => '•••• 4821 0377'],
                ['label' => 'Currency', 'value' => 'USD'],
                ['label' => 'Exact Amount', 'value' => '$500.00'],
                ['label' => 'Transaction Reference', 'value' => 'AVCD-702913'],
            ],
            'requiredAction' => 'Make the payment and upload your payment confirmation.',
        ],
        [
            'reference' => 'AVCD-640138', 'listingReference' => 'AVC-J2M9B017',
            'role' => 'seller', 'status' => 'seller_confirmed', 'statusLabel' => 'Seller Confirmed — Awaiting Admin Release',
            'avcAmount' => 1000, 'paymentMethod' => 'USDT', 'currency' => 'USDT',
            'paymentAmount' => 1000.00, 'escrowStatus' => 'Secured in Escrow',
            'counterparty' => 'Daniel K.**', 'currentStage' => 7, 'deadlineSeconds' => null,
            'paymentInstructions' => [],
            'requiredAction' => 'Authorize the release of AVC held in escrow once payment is confirmed.',
        ],
        [
            'reference' => 'AVCD-532991', 'listingReference' => 'AVC-K9J0P771',
            'role' => 'buyer', 'status' => 'completed', 'statusLabel' => 'Deal Completed',
            'avcAmount' => 300, 'paymentMethod' => 'Bank Transfer', 'currency' => 'EUR',
            'paymentAmount' => 300.00, 'escrowStatus' => 'Released to Buyer',
            'counterparty' => 'Maria G.**', 'currentStage' => 9, 'deadlineSeconds' => null,
            'paymentInstructions' => [],
            'requiredAction' => 'This deal has been completed.',
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'balanceSummary' => [
                ['label' => 'Available AVC', 'value' => '26,900 AVC', 'caption' => 'Excludes reserved AVC', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-blue-500'],
                ['label' => 'AVC Locked in Escrow', 'value' => '500 AVC', 'caption' => 'Held by the platform', 'icon' => 'heroicon-o-lock-closed', 'color' => 'bg-amber-500'],
                ['label' => 'Active Deals', 'value' => 1, 'caption' => '1 awaiting payment', 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-emerald-500'],
            ],
            'listings' => $this->listings,
            'filters' => [
                'countries' => ['Philippines', 'United Arab Emirates', 'Spain', 'United Kingdom', 'Singapore', 'Canada', 'United States'],
                'methods' => ['Bank Transfer', 'PayPal', 'GCash', 'Mobile Money', 'Cryptocurrency'],
                'currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'USDT', 'PHP'],
            ],
            'escrow' => [
                'telegram' => Setting::get('escrow_telegram_link', 'https://t.me/avc_escrow'),
                'whatsapp' => Setting::get('escrow_whatsapp_link', 'https://wa.me/18005550134'),
            ],
            'howItWorks' => [
                ['icon' => 'heroicon-o-plus-circle', 'title' => 'Create a Listing', 'detail' => 'Sell or buy AVC through the marketplace. Admin reviews every listing before it goes live.'],
                ['icon' => 'heroicon-o-chat-bubble-left-right', 'title' => 'Deal via Admin', 'detail' => 'Contact the Admin Escrow Team on Telegram or WhatsApp. No direct buyer–seller messaging.'],
                ['icon' => 'heroicon-o-lock-closed', 'title' => 'AVC Secured in Escrow', 'detail' => 'AVC is locked by the platform until both parties confirm the transaction.'],
                ['icon' => 'heroicon-o-check-circle', 'title' => 'Payment Confirmed', 'detail' => 'The buyer pays the approved destination. The seller confirms receipt and authorizes release.'],
                ['icon' => 'heroicon-o-arrow-down-tray', 'title' => 'AVC Released', 'detail' => 'The Admin Escrow Team performs the final review and releases AVC to the buyer.'],
            ],
        ];

        return view('marketplace.avc.index', $data);
    }

    public function create()
    {
        $user = Auth::user();

        return view('marketplace.avc.create', [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'paymentMethods' => ['Bank Transfer', 'PayPal', 'GCash', 'Mobile Money', 'Cryptocurrency', 'Other approved method'],
            'currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'PHP', 'NGN', 'AED', 'SGD', 'USDT'],
            'countries' => ['United States', 'United Kingdom', 'Philippines', 'Spain', 'United Arab Emirates', 'Singapore', 'Canada', 'Nigeria'],
        ]);
    }

    public function myListings()
    {
        $user = Auth::user();

        $listings = [
            ['reference' => 'AVC-2D5F7H44', 'type' => 'sell', 'amount' => 500, 'remaining' => 500, 'paymentMethod' => 'Bank Transfer', 'escrowStatus' => 'Secured in Escrow', 'status' => 'live', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'createdAt' => 'Aug 08, 2026'],
            ['reference' => 'AVC-1A3C5E55', 'type' => 'sell', 'amount' => 1200, 'remaining' => 1200, 'paymentMethod' => 'USDT', 'escrowStatus' => 'Awaiting Escrow', 'status' => 'pending_review', 'statusColor' => 'bg-amber-50 text-amber-700 ring-amber-200', 'createdAt' => 'Aug 07, 2026'],
            ['reference' => 'AVC-7H9J1K66', 'type' => 'buy', 'amount' => 400, 'remaining' => 400, 'paymentMethod' => 'GCash', 'escrowStatus' => '—', 'status' => 'changes_required', 'statusColor' => 'bg-orange-50 text-orange-700 ring-orange-200', 'createdAt' => 'Aug 05, 2026'],
            ['reference' => 'AVC-3M5N7P77', 'type' => 'sell', 'amount' => 900, 'remaining' => 0, 'paymentMethod' => 'Bank Transfer', 'escrowStatus' => 'Released', 'status' => 'completed', 'statusColor' => 'bg-slate-100 text-slate-600 ring-slate-200', 'createdAt' => 'Jul 28, 2026'],
        ];

        return view('marketplace.avc.my-listings', [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'listings' => $listings,
        ]);
    }

    public function myDeals()
    {
        $user = Auth::user();

        $deals = [
            ['reference' => 'AVCD-702913', 'role' => 'Buyer', 'listing' => 'AVC-8K01Z036', 'amount' => 500, 'method' => 'Bank Transfer', 'status' => 'Awaiting Payment', 'statusColor' => 'bg-amber-50 text-amber-700 ring-amber-200', 'action' => 'View Deal'],
            ['reference' => 'AVCD-640138', 'role' => 'Seller', 'listing' => 'AVC-J2M9B017', 'amount' => 1000, 'method' => 'USDT', 'status' => 'Seller Confirmed', 'statusColor' => 'bg-blue-50 text-blue-700 ring-blue-200', 'action' => 'View Deal'],
            ['reference' => 'AVCD-532991', 'role' => 'Buyer', 'listing' => 'AVC-K9J0P771', 'amount' => 300, 'method' => 'Bank Transfer', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'action' => 'View Receipt'],
        ];

        return view('marketplace.avc.my-deals', [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'deals' => $deals,
        ]);
    }

    public function deal(string $reference)
    {
        $user = Auth::user();
        $deal = collect($this->deals)->firstWhere('reference', $reference);

        if (! $deal) {
            abort(404);
        }

        return view('marketplace.avc.deal', [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'deal' => $deal,
        ]);
    }

    protected function initials(string $name): string
    {
        $parts = array_values(array_filter(preg_split('/\s+/', trim($name))));
        if (count($parts) === 0) {
            return 'NE';
        }

        $initials = strtoupper(substr($parts[0], 0, 1));
        if (count($parts) > 1) {
            $initials .= strtoupper(substr($parts[count($parts) - 1], 0, 1));
        }

        return $initials;
    }
}
