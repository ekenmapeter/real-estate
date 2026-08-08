<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AffiliateController extends Controller
{
    protected array $sections = [
        'my-referrals' => ['label' => 'My Referrals', 'icon' => 'heroicon-o-user-group'],
        'assigned-projects' => ['label' => 'Assigned Projects', 'icon' => 'heroicon-o-building-office-2'],
        'referral-link' => ['label' => 'Referral Link', 'icon' => 'heroicon-o-link'],
        'referral-code' => ['label' => 'Referral Code', 'icon' => 'heroicon-o-qr-code'],
        'qr-code' => ['label' => 'QR Code', 'icon' => 'heroicon-o-qr-code'],
        'media-library' => ['label' => 'Media Library', 'icon' => 'heroicon-o-photo'],
        'promo-builder' => ['label' => 'Promo Builder', 'icon' => 'heroicon-o-swatch'],
        'downloads' => ['label' => 'Downloads', 'icon' => 'heroicon-o-arrow-down-tray'],
        'finance-requests' => ['label' => 'Finance Requests', 'icon' => 'heroicon-o-banknotes'],
        'support-history' => ['label' => 'Support History', 'icon' => 'heroicon-o-chat-bubble-left-right'],
        'commission-wallet' => ['label' => 'Commission Wallet', 'icon' => 'heroicon-o-wallet'],
        'withdrawals' => ['label' => 'Withdrawals', 'icon' => 'heroicon-o-arrow-up-tray'],
        'referral-history' => ['label' => 'Referral History', 'icon' => 'heroicon-o-clock'],
        'commission-history' => ['label' => 'Commission History', 'icon' => 'heroicon-o-banknotes'],
        'finance-history' => ['label' => 'Finance History', 'icon' => 'heroicon-o-clock'],
        'downloads-history' => ['label' => 'Downloads History', 'icon' => 'heroicon-o-arrow-down-tray'],
        'profile-settings' => ['label' => 'Profile Settings', 'icon' => 'heroicon-o-user-circle'],
        'notification-settings' => ['label' => 'Notification Settings', 'icon' => 'heroicon-o-bell'],
    ];

    public function index()
    {
        $user = Auth::user();
        $code = $user->affiliate_code ?? 'AVC483927';
        $trackingUrl = url('/register?ref=' . $code);

        $data = [
            'user' => $user,
            'affiliate' => [
                'name' => $user->name ?? 'Nelson E.',
                'initials' => $this->initials($user->name ?? 'Nelson E.'),
                'level' => 'Gold Partner',
                'commissionRate' => 20,
                'rating' => 4.9,
                'memberSince' => 'March 2026',
                'status' => 'Active Affiliate',
            ],
            'commissionStats' => [
                'available' => 5480.00,
                'pending' => 720.00,
                'lifetime' => 48965.00,
            ],
            'performanceStats' => [
                ['key' => 'visitors', 'icon' => 'heroicon-o-eye', 'color' => 'bg-blue-500', 'label' => 'Visitors', 'value' => 12548, 'trend' => 18.6],
                ['key' => 'leads', 'icon' => 'heroicon-o-cursor-arrow-rays', 'color' => 'bg-violet-500', 'label' => 'Qualified Leads', 'value' => 985, 'trend' => 14.3],
                ['key' => 'registered', 'icon' => 'heroicon-o-user-plus', 'color' => 'bg-emerald-500', 'label' => 'Registered Investors', 'value' => 421, 'trend' => 21.7],
                ['key' => 'investments', 'icon' => 'heroicon-o-arrow-trending-up', 'color' => 'bg-orange-500', 'label' => 'Successful Investments', 'value' => 196, 'trend' => 23.9],
            ],
            'funnel' => [
                'stages' => [
                    ['label' => 'Visitors', 'value' => 12548, 'icon' => 'heroicon-o-eye'],
                    ['label' => 'Leads', 'value' => 985, 'icon' => 'heroicon-o-cursor-arrow-rays'],
                    ['label' => 'Registered', 'value' => 421, 'icon' => 'heroicon-o-user-plus'],
                    ['label' => 'Verified', 'value' => 317, 'icon' => 'heroicon-o-shield-check'],
                    ['label' => 'Purchased', 'value' => 196, 'icon' => 'heroicon-o-arrow-trending-up'],
                ],
                'conversionRate' => 46,
                'conversionTrend' => 12.4,
            ],
            'monthlyEarnings' => [
                'ranges' => [
                    '1M' => ['labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'commission' => [6200, 6800, 5400, 7300, 7900, 8600, 9200, 9850], 'deposits' => [5200, 5800, 6200, 7100, 7600, 8300, 8900, 9400], 'trend' => 18.6],
                    '3M' => ['labels' => ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'commission' => [5400, 7300, 7900, 8600, 9200, 9850], 'deposits' => [6200, 7100, 7600, 8300, 8900, 9400], 'trend' => 12.4],
                    '6M' => ['labels' => ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'commission' => [5400, 7300, 7900, 8600, 9200, 9850], 'deposits' => [6200, 7100, 7600, 8300, 8900, 9400], 'trend' => -7.2],
                    '1Y' => ['labels' => ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'commission' => [4100, 4800, 5100, 5600, 6200, 6800, 5400, 7300, 7900, 8600, 9200, 9850], 'deposits' => [3800, 4200, 4600, 5000, 5200, 5800, 6200, 7100, 7600, 8300, 8900, 9400], 'trend' => 21.3],
                    'ALL' => ['labels' => ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'], 'commission' => [4100, 4800, 5100, 5600, 6200, 6800, 5400, 7300, 7900, 8600, 9200, 9850], 'deposits' => [3800, 4200, 4600, 5000, 5200, 5800, 6200, 7100, 7600, 8300, 8900, 9400], 'trend' => 34.7],
                ],
            ],
            'assignedProjects' => [
                [
                    'title' => 'Luxury Villas',
                    'location' => 'Marbella, Spain',
                    'flag' => '🇪🇸',
                    'status' => 'Sale',
                    'image_url' => null,
                    'gradient' => 'from-blue-700 via-indigo-700 to-blue-900',
                    'progress' => 82,
                    'investors' => 18,
                    'generated' => 86500.00,
                    'commission' => 6400.00,
                    'deadline' => '45 days remaining',
                    'campaign' => 'Active',
                    'project_id' => null,
                ],
                [
                    'title' => 'Urban Living Apartments',
                    'location' => 'Dubai, UAE',
                    'flag' => '🇦🇪',
                    'status' => 'Sale',
                    'image_url' => null,
                    'gradient' => 'from-sky-700 via-blue-700 to-slate-900',
                    'progress' => 64,
                    'investors' => 12,
                    'generated' => 48500.00,
                    'commission' => 4100.00,
                    'deadline' => '28 days remaining',
                    'campaign' => 'Active',
                    'project_id' => null,
                ],
                [
                    'title' => 'Beachfront Resort',
                    'location' => 'Bali, Indonesia',
                    'flag' => '🇮🇩',
                    'status' => 'Sale',
                    'image_url' => null,
                    'gradient' => 'from-teal-700 via-cyan-700 to-blue-900',
                    'progress' => 45,
                    'investors' => 9,
                    'generated' => 31200.00,
                    'commission' => 2650.00,
                    'deadline' => '62 days remaining',
                    'campaign' => 'Active',
                    'project_id' => null,
                ],
            ],
            'referralLink' => $trackingUrl,
            'referralCode' => $code,
            'qrSvg' => $this->qrCode($trackingUrl),
            'recentReferrals' => [
                ['name' => 'John Smith', 'country' => 'United Kingdom', 'flag' => '🇬🇧', 'status' => 'commission_paid', 'investment' => 5000.00, 'commission' => 1000.00, 'date' => 'Jul 18, 2026'],
                ['name' => 'Maria Garcia', 'country' => 'Spain', 'flag' => '🇪🇸', 'status' => 'verified', 'investment' => 2500.00, 'commission' => 500.00, 'date' => 'Jul 14, 2026'],
                ['name' => 'Ahmed Khan', 'country' => 'United Arab Emirates', 'flag' => '🇦🇪', 'status' => 'deposit_pending', 'investment' => 4000.00, 'commission' => 800.00, 'date' => 'Jul 09, 2026'],
                ['name' => 'Jessica Lee', 'country' => 'Singapore', 'flag' => '🇸🇬', 'status' => 'pending_kyc', 'investment' => 0.00, 'commission' => 0.00, 'date' => 'Jul 05, 2026'],
                ['name' => 'Robert Brown', 'country' => 'Canada', 'flag' => '🇨🇦', 'status' => 'registered', 'investment' => 0.00, 'commission' => 0.00, 'date' => 'Jun 29, 2026'],
            ],
            'financeRequests' => [
                ['id' => 'AFR-2026-0142', 'investor' => 'John Smith', 'amount' => 5000.00, 'status' => 'payment_details_active', 'seconds' => 540],
                ['id' => 'AFR-2026-0138', 'investor' => 'Maria Garcia', 'amount' => 2500.00, 'status' => 'completed', 'seconds' => null],
                ['id' => 'AFR-2026-0131', 'investor' => 'Ahmed Khan', 'amount' => 4000.00, 'status' => 'waiting_proof', 'seconds' => null],
            ],
            'benefits' => [
                ['icon' => 'heroicon-o-banknotes', 'title' => 'Higher Commission', 'caption' => 'Earn up to 30% commission'],
                ['icon' => 'heroicon-o-lifebuoy', 'title' => 'Priority Support', 'caption' => 'Get priority from Finance Team'],
                ['icon' => 'heroicon-o-rocket-launch', 'title' => 'Exclusive Projects', 'caption' => 'Access premium projects'],
                ['icon' => 'heroicon-o-trophy', 'title' => 'Monthly Bonuses', 'caption' => 'Earn performance bonuses'],
            ],
            'sectionCatalog' => $this->sections,
        ];

        return view('affiliate.overview', $data);
    }

    public function section(string $section)
    {
        if (! array_key_exists($section, $this->sections)) {
            abort(404);
        }

        $user = Auth::user();
        $code = $user->affiliate_code ?? 'AVC483927';

        return view('affiliate.section', [
            'user' => $user,
            'sectionKey' => $section,
            'section' => $this->sections[$section],
            'sectionCatalog' => $this->sections,
            'affiliate' => [
                'name' => $user->name ?? 'Nelson E.',
                'initials' => $this->initials($user->name ?? 'Nelson E.'),
                'level' => 'Gold Partner',
                'commissionRate' => 20,
                'rating' => 4.9,
                'memberSince' => 'March 2026',
                'status' => 'Active Affiliate',
            ],
            'referralLink' => url('/register?ref=' . $code),
            'referralCode' => $code,
        ]);
    }

    protected function qrCode(string $url): string
    {
        try {
            return QrCode::format('svg')->size(150)->margin(0)->generate($url);
        } catch (\Throwable $e) {
            return '';
        }
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
