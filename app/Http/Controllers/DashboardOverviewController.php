<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardOverviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $name = $user->name ?? 'new';
        $balance = (float) ($user->wallet_balance ?? 0);
        if ($balance <= 0) {
            $balance = 500;
        }

        $hour = (int) now()->format('G');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');

        $data = [
            'user' => $user,
            'showTourAuto' => $this->shouldAutoShowTour($user),
            'profile' => [
                'name' => $name,
                'initials' => $this->initials($name),
            ],
            'header' => [
                'greeting' => $greeting,
                'investorId' => $user->account_id ?? 'RDR-209311',
                'kycVerified' => (bool) ($user->kyc_verified ?? false),
                'kycStatus' => $user->kyc_status ?? 'pending',
                'avcPrice' => '1 AVC = $1.00 USD',
            ],
            'balance' => [
                'total' => $balance,
                'usd' => $balance,
                'rate' => '1.0000',
                'totalDeposited' => 4850.00,
                'totalWithdrawn' => 1200.00,
                'available' => $balance,
                'pending' => 0.00,
            ],
            'quickActions' => [
                ['label' => 'Deposit AVC', 'href' => route('deposit.index'), 'icon' => 'heroicon-o-arrow-down-tray', 'color' => 'bg-blue-100 text-blue-600'],
                ['label' => 'Withdraw AVC', 'href' => route('withdraw.index'), 'icon' => 'heroicon-o-arrow-up-tray', 'color' => 'bg-rose-100 text-rose-600', 'id' => 'tour-withdraw'],
                ['label' => 'Send AVC', 'href' => route('transfer.send'), 'icon' => 'heroicon-o-paper-airplane', 'color' => 'bg-indigo-100 text-indigo-600'],
                ['label' => 'Receive AVC', 'href' => route('transfer.receive'), 'icon' => 'heroicon-o-qr-code', 'color' => 'bg-violet-100 text-violet-600'],
                ['label' => 'Buy Project Shares', 'href' => route('marketplace.index'), 'icon' => 'heroicon-o-building-office-2', 'color' => 'bg-emerald-100 text-emerald-600', 'id' => 'tour-marketplace'],
                ['label' => 'AVC Marketplace', 'href' => route('avc-marketplace.index'), 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-amber-100 text-amber-600', 'id' => 'tour-sell'],
                ['label' => 'Properties Marketplace', 'href' => url('/properties'), 'icon' => 'heroicon-o-building-office', 'color' => 'bg-cyan-100 text-cyan-600'],
                ['label' => 'Finance Team', 'href' => route('finance.team.index'), 'icon' => 'heroicon-o-banknotes', 'color' => 'bg-teal-100 text-teal-600'],
                ['label' => 'Documents', 'href' => route('documents.index'), 'icon' => 'heroicon-o-folder', 'color' => 'bg-sky-100 text-sky-600'],
                ['label' => 'My AVC Card', 'href' => route('dashboard') . '#avc-card', 'icon' => 'heroicon-o-credit-card', 'color' => 'bg-fuchsia-100 text-fuchsia-600'],
                ['label' => 'Affiliate Dashboard', 'href' => route('affiliate.center'), 'icon' => 'heroicon-o-user-group', 'color' => 'bg-purple-100 text-purple-600'],
                ['label' => 'Support', 'href' => route('support.index'), 'icon' => 'heroicon-o-lifebuoy', 'color' => 'bg-slate-100 text-slate-600'],
            ],
            'portfolio' => [
                'totalValue' => 24500.00,
                'activeProjects' => 3,
                'completedProjects' => 1,
                'totalShares' => 145,
                'portfolioRoi' => 12.4,
                'totalRoiEarned' => 750.00,
                'dividendEarnings' => 320.00,
                'rentalEarnings' => 430.00,
            ],
            'earningsChart' => [
                'ranges' => [
                    '1M' => ['labels' => ['Aug 2', 'Aug 3', 'Aug 4', 'Aug 5', 'Aug 6', 'Aug 7', 'Aug 8'], 'roi' => [120, 180, 140, 220, 260, 310, 350], 'dividends' => [40, 40, 40, 40, 40, 40, 40], 'rental' => [60, 60, 60, 60, 60, 60, 60]],
                    '3M' => ['labels' => ['Jun', 'Jul', 'Aug'], 'roi' => [520, 640, 750], 'dividends' => [120, 120, 120], 'rental' => [180, 180, 180]],
                    '6M' => ['labels' => ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'roi' => [1800, 2100, 2400, 2800, 3200, 3650], 'dividends' => [720, 720, 720, 720, 720, 720], 'rental' => [1080, 1080, 1080, 1080, 1080, 1080]],
                    '1Y' => ['labels' => ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'roi' => [3400, 3800, 4100, 4500, 4900, 5200, 5600, 6100, 6500, 7000, 7400, 7800], 'dividends' => [1440, 1440, 1440, 1440, 1440, 1440, 1440, 1440, 1440, 1440, 1440, 1440], 'rental' => [2160, 2160, 2160, 2160, 2160, 2160, 2160, 2160, 2160, 2160, 2160, 2160]],
                    'ALL' => ['labels' => ['2024', '2025', '2026'], 'roi' => [12500, 21000, 28900], 'dividends' => [4200, 4800, 5200], 'rental' => [5600, 6400, 7000]],
                ],
            ],
            'activeInvestments' => [
                ['title' => 'Luxury Villas', 'location' => 'Marbella, Spain', 'flag' => '🇪🇸', 'amount' => 8500.00, 'shares' => 85, 'roi' => 16.0, 'progress' => 82, 'status' => 'Active', 'gradient' => 'from-blue-700 via-indigo-700 to-blue-900', 'image_url' => null, 'href' => route('marketplace.index')],
                ['title' => 'Urban Living Apartments', 'location' => 'Dubai, UAE', 'flag' => '🇦🇪', 'amount' => 12000.00, 'shares' => 120, 'roi' => 14.0, 'progress' => 64, 'status' => 'Active', 'gradient' => 'from-sky-700 via-blue-700 to-slate-900', 'image_url' => null, 'href' => route('marketplace.index')],
                ['title' => 'Beachfront Resort', 'location' => 'Bali, Indonesia', 'flag' => '🇮🇩', 'amount' => 4000.00, 'shares' => 40, 'roi' => 18.0, 'progress' => 45, 'status' => 'Active', 'gradient' => 'from-teal-700 via-cyan-700 to-blue-900', 'image_url' => null, 'href' => route('marketplace.index')],
            ],
            'avcCard' => [
                'status' => 'not_applied',
                'balance' => 1250.00,
                'dailyLimit' => '500.00',
                'lastFour' => '4821',
            ],
            'affiliate' => [
                'commissionBalance' => 5480.00,
                'totalReferrals' => 421,
                'qualifiedLeads' => 985,
                'totalVisitors' => 12548,
                'conversionRate' => 46,
                'rating' => 4.9,
            ],
            'financeRequests' => [
                ['id' => 'FR-260806-8K2M', 'type' => 'Deposit', 'status' => 'Under Review', 'statusColor' => 'bg-blue-50 text-blue-700 ring-blue-200', 'amount' => 2500.00, 'date' => 'Aug 06, 2026'],
                ['id' => 'FR-260804-9Q1L', 'type' => 'Withdrawal', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'amount' => 800.00, 'date' => 'Aug 04, 2026'],
            ],
            'recentActivity' => [
                ['icon' => 'heroicon-o-arrow-trending-up', 'color' => 'bg-emerald-100 text-emerald-600', 'label' => 'ROI received', 'detail' => 'Luxury Villas · cycle payout', 'date' => '2 hours ago'],
                ['icon' => 'heroicon-o-banknotes', 'color' => 'bg-amber-100 text-amber-600', 'label' => 'Dividend received', 'detail' => 'Quarterly dividend distribution', 'date' => 'Yesterday'],
                ['icon' => 'heroicon-o-shopping-bag', 'color' => 'bg-blue-100 text-blue-600', 'label' => 'Purchased shares', 'detail' => '40 shares · Beachfront Resort', 'date' => 'Jul 28'],
                ['icon' => 'heroicon-o-check-circle', 'color' => 'bg-emerald-100 text-emerald-600', 'label' => 'Deposit approved', 'detail' => 'DEP-2026-000101 · $500.00', 'date' => 'Jul 25'],
                ['icon' => 'heroicon-o-paper-airplane', 'color' => 'bg-indigo-100 text-indigo-600', 'label' => 'AVC sent', 'detail' => '500 AVC → Sarah Jenkins', 'date' => 'Jul 20'],
                ['icon' => 'heroicon-o-gift', 'color' => 'bg-purple-100 text-purple-600', 'label' => 'Affiliate commission earned', 'detail' => '$1,000.00 · John Smith', 'date' => 'Jul 18'],
            ],
            'recentTransactions' => [
                ['label' => 'Deposit', 'description' => 'Bank transfer deposit', 'date' => 'Jul 25, 10:12', 'amount' => 500.00, 'type' => 'credit', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
                ['label' => 'Share Purchase', 'description' => '40 shares · Beachfront Resort', 'date' => 'Jul 28, 14:03', 'amount' => 4000.00, 'type' => 'debit', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
                ['label' => 'ROI Payout', 'description' => 'Luxury Villas cycle payout', 'date' => 'Aug 06, 09:00', 'amount' => 350.00, 'type' => 'credit', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
                ['label' => 'AVC Transfer Sent', 'description' => 'To Sarah Jenkins', 'date' => 'Jul 20, 16:22', 'amount' => 500.00, 'type' => 'debit', 'status' => 'Pending', 'statusColor' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                ['label' => 'Withdrawal', 'description' => 'Bank transfer payout', 'date' => 'Jul 15, 11:30', 'amount' => 800.00, 'type' => 'debit', 'status' => 'Completed', 'statusColor' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
            ],
            'marketHighlights' => [
                'avcPrice' => '1.00',
                'totalProjects' => 8,
                'totalProperties' => 142,
                'marketplaceListings' => 23,
                'newProjects' => 2,
                'featuredProject' => 'Luxury Villas · Marbella, Spain',
                'featuredHref' => route('marketplace.index'),
            ],
            'documents' => [
                'kycStatus' => 'Pending',
                'kycStatusColor' => 'bg-amber-50 text-amber-700 ring-amber-200',
                'amlStatus' => 'Not Started',
                'amlStatusColor' => 'bg-slate-100 text-slate-500 ring-slate-200',
                'uploadedDocuments' => 4,
                'verificationLevel' => 1,
                'progress' => 60,
            ],
            'statsFooter' => [
                ['label' => 'Total Portfolio Value', 'value' => '$24,500.00'],
                ['label' => 'Total ROI Earned', 'value' => '$750.00'],
                ['label' => 'Total AVC Deposited', 'value' => '$4,850.00'],
                ['label' => 'Total AVC Withdrawn', 'value' => '$1,200.00'],
                ['label' => 'Affiliate Earnings', 'value' => '$5,480.00'],
                ['label' => 'Projects Invested', 'value' => 3],
                ['label' => 'Properties Owned', 'value' => 0],
                ['label' => 'Member Since', 'value' => optional($user->created_at)->format('M Y') ?: 'Aug 2026'],
            ],
        ];

        return view('dashboard.overview', $data);
    }

    public function completeTour()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->guided_tour_completed_at = now();
        $user->save();

        return redirect()->route('dashboard');
    }

    public function skipTour()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->guided_tour_skipped_at = now();
        $user->save();

        return redirect()->route('dashboard');
    }

    protected function shouldAutoShowTour($user): bool
    {
        if ($user->guided_tour_completed_at || $user->guided_tour_skipped_at) {
            return false;
        }

        $registeredWithin = $user->created_at && $user->created_at->gte(now()->subDays(7));

        return (bool) $registeredWithin;
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
