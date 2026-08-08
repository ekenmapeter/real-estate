<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hasName = (bool) ($user->name ?? null);

        $name = $user->name ?? 'new';
        $email = $user->email ?? 'kofiadjo09@gmail.com';
        $accountId = $user->account_id ?? 'RDR-209311';
        $referralCode = $user->affiliate_code ?? 'AVC8X7K2';
        $initials = $hasName ? $this->initials($user->name) : 'NE';

        $avcBalance = (float) ($user->wallet_balance ?? 0);
        if ($avcBalance <= 0) {
            $avcBalance = 500;
        }

        $affiliateEarnings = (float) ($user->affiliate_earnings ?? 0);
        if ($affiliateEarnings <= 0) {
            $affiliateEarnings = 120;
        }

        $activeInvestments = $user->projectInvestments()->count();
        if ($activeInvestments <= 0) {
            $activeInvestments = 3;
        }

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $name,
                'initials' => $initials,
                'email' => $email,
                'accountId' => $accountId,
                'referralCode' => $referralCode,
                'referralLink' => url('/register?ref=' . $referralCode),
                'memberSince' => optional($user->created_at)->format('M d, Y') ?: 'Aug 03, 2026',
                'country' => 'United States',
                'preferredCurrency' => strtoupper($user->preferred_currency ?? 'USD'),
                'language' => 'English',
                'timezone' => 'UTC',
                'phone' => null,
                'dateOfBirth' => null,
                'gender' => null,
                'nationality' => null,
                'lastLogin' => '2 hours ago',
                'lastPasswordChange' => '2 days ago',
            ],
            'stats' => [
                ['key' => 'avc', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-blue-500', 'label' => 'AVC Balance', 'value' => number_format($avcBalance) . ' AVC', 'caption' => '≈ $' . number_format($avcBalance, 2) . ' USD'],
                ['key' => 'portfolio', 'icon' => 'heroicon-o-arrow-trending-up', 'color' => 'bg-emerald-500', 'label' => 'Total Portfolio Value', 'value' => '$24,500.00 USD'],
                ['key' => 'investments', 'icon' => 'heroicon-o-chart-pie', 'color' => 'bg-violet-500', 'label' => 'Active Investments', 'value' => $activeInvestments, 'caption' => 'Projects'],
                ['key' => 'lifetime', 'icon' => 'heroicon-o-currency-dollar', 'color' => 'bg-orange-500', 'label' => 'Lifetime Earnings', 'value' => '$750.00 USD'],
                ['key' => 'affiliate', 'icon' => 'heroicon-o-users', 'color' => 'bg-rose-500', 'label' => 'Affiliate Earnings', 'value' => '$' . number_format($affiliateEarnings, 2) . ' USD'],
            ],
            'pending' => [
                ['label' => 'Pending Deposits', 'value' => '$0.00 USD', 'href' => route('deposit.index')],
                ['label' => 'Pending Withdrawals', 'value' => '$0.00 USD', 'href' => route('withdraw.index')],
                ['label' => 'KYC Status', 'value' => 'Pending', 'href' => null],
            ],
            'kyc' => [
                'status' => $user->kyc_status ?? 'pending',
                'statusLabel' => 'Pending',
                'progress' => 60,
                'steps' => [
                    ['title' => 'Personal Information', 'status' => 'done', 'note' => 'Completed'],
                    ['title' => 'Government ID', 'status' => 'pending', 'note' => 'In progress'],
                    ['title' => 'Selfie Verification', 'status' => 'pending', 'note' => 'Not started'],
                    ['title' => 'Proof of Address', 'status' => 'pending', 'note' => 'Not started'],
                    ['title' => 'Additional Verification', 'status' => 'optional', 'note' => 'Optional'],
                ],
            ],
            'linkedAccounts' => [
                ['provider' => 'google', 'label' => 'Google', 'connected' => true, 'date' => 'Connected · Aug 2026'],
                ['provider' => 'apple', 'label' => 'Apple', 'connected' => false, 'date' => 'Not connected'],
                ['provider' => 'telegram', 'label' => 'Telegram', 'connected' => true, 'date' => 'Connected · Jul 2026'],
                ['provider' => 'whatsapp', 'label' => 'WhatsApp', 'connected' => false, 'date' => 'Not connected'],
            ],
            'security' => [
                ['key' => 'password', 'label' => 'Password', 'note' => 'Last changed 2 days ago', 'icon' => 'heroicon-o-check-circle', 'color' => 'text-emerald-500'],
                ['key' => 'twofa', 'label' => 'Two-Factor Authentication (2FA)', 'note' => 'Enabled', 'icon' => 'heroicon-o-check-circle', 'color' => 'text-emerald-500'],
                ['key' => 'sessions', 'label' => 'Active Sessions', 'note' => '3 active sessions', 'icon' => 'heroicon-o-check-circle', 'color' => 'text-emerald-500'],
                ['key' => 'login_activity', 'label' => 'Login Activity', 'note' => 'View recent login activity', 'icon' => 'heroicon-o-clock', 'color' => 'text-blue-500'],
            ],
            'quickActions' => [
                ['key' => 'download', 'label' => 'Download My Data', 'icon' => 'heroicon-o-arrow-down-tray', 'color' => 'text-blue-600', 'destructive' => false],
                ['key' => 'delete', 'label' => 'Request Account Deletion', 'icon' => 'heroicon-o-trash', 'color' => 'text-rose-600', 'destructive' => true],
                ['key' => 'logout_all', 'label' => 'Logout All Devices', 'icon' => 'heroicon-o-arrow-left-on-rectangle', 'color' => 'text-slate-600', 'destructive' => false],
            ],
        ];

        return view('settings.profile', $data);
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
