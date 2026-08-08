<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    protected array $categories = [
        ['key' => 'account', 'label' => 'Account & Profile', 'description' => 'Profile, login, account access and membership', 'icon' => 'heroicon-o-user-circle', 'color' => 'bg-blue-50 text-blue-600'],
        ['key' => 'kyc', 'label' => 'KYC & Verification', 'description' => 'Identity verification and document reviews', 'icon' => 'heroicon-o-identification', 'color' => 'bg-indigo-50 text-indigo-600'],
        ['key' => 'deposit', 'label' => 'Deposit Support', 'description' => 'AVC deposits and payment instructions', 'icon' => 'heroicon-o-arrow-down-tray', 'color' => 'bg-emerald-50 text-emerald-600'],
        ['key' => 'withdrawal', 'label' => 'Withdrawal Support', 'description' => 'AVC withdrawals and payouts', 'icon' => 'heroicon-o-arrow-up-tray', 'color' => 'bg-rose-50 text-rose-600'],
        ['key' => 'project', 'label' => 'Project Investment', 'description' => 'Share purchases, cycles and returns', 'icon' => 'heroicon-o-building-office-2', 'color' => 'bg-violet-50 text-violet-600'],
        ['key' => 'marketplace', 'label' => 'AVC Marketplace', 'description' => 'Credit swaps and escrow deals', 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-amber-50 text-amber-600'],
        ['key' => 'property', 'label' => 'Property Support', 'description' => 'Listings, inquiries and ownership', 'icon' => 'heroicon-o-building-office', 'color' => 'bg-cyan-50 text-cyan-600'],
        ['key' => 'affiliate', 'label' => 'Affiliate Support', 'description' => 'Referrals, commissions and campaigns', 'icon' => 'heroicon-o-user-group', 'color' => 'bg-purple-50 text-purple-600'],
        ['key' => 'finance_team', 'label' => 'Finance Team', 'description' => 'Manual deposits and finance requests', 'icon' => 'heroicon-o-banknotes', 'color' => 'bg-teal-50 text-teal-600'],
        ['key' => 'technical', 'label' => 'Technical Support', 'description' => 'Bugs, errors and platform issues', 'icon' => 'heroicon-o-wrench-screwdriver', 'color' => 'bg-slate-100 text-slate-600'],
        ['key' => 'general', 'label' => 'General Inquiry', 'description' => 'Questions and general information', 'icon' => 'heroicon-o-chat-bubble-left-right', 'color' => 'bg-sky-50 text-sky-600'],
        ['key' => 'security', 'label' => 'Security & Reports', 'description' => 'Suspicious activity and account safety', 'icon' => 'heroicon-o-shield-exclamation', 'color' => 'bg-red-50 text-red-600'],
    ];

    protected array $requests = [
        [
            'reference' => 'AVC-SUP-2026-008421',
            'subject' => 'Withdrawal is still pending after 48 hours',
            'category' => 'Withdrawal Support',
            'status' => 'in_progress',
            'lastUpdate' => '2 hours ago',
            'priority' => 'High',
            'attachments' => ['withdrawal_receipt.pdf', 'bank_statement_masked.png'],
            'history' => [
                ['at' => 'Jul 28, 10:12', 'label' => 'Request submitted', 'status' => 'Open'],
                ['at' => 'Jul 28, 10:15', 'label' => 'Assigned to Finance Team', 'status' => 'In Progress'],
                ['at' => 'Jul 28, 11:40', 'label' => 'Finance Team requested proof of payment', 'status' => 'Awaiting User'],
                ['at' => 'Jul 29, 09:05', 'label' => 'Proof of payment uploaded', 'status' => 'In Progress'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 28, 10:12', 'body' => 'Hi, I submitted a withdrawal request (WDR-2026-004182) two days ago and it is still showing as under review. Can you check the status?'],
                ['from' => 'support', 'at' => 'Jul 28, 11:40', 'body' => 'Hello! Thanks for reaching out. Your withdrawal is with the Finance Team for verification. Could you upload the bank transfer receipt and a masked bank statement so we can confirm the payout details?'],
                ['from' => 'user', 'at' => 'Jul 29, 09:05', 'body' => 'Sure, I have attached both documents. Please let me know if anything else is needed.'],
                ['from' => 'support', 'at' => 'Jul 29, 09:20', 'body' => 'Received, thank you! The Finance Team is reviewing them now. You will receive a confirmation as soon as your withdrawal is approved.'],
            ],
        ],
        [
            'reference' => 'AVC-SUP-2026-008419',
            'subject' => 'Unable to upload my passport for KYC',
            'category' => 'KYC & Verification',
            'status' => 'awaiting_user',
            'lastUpdate' => 'Yesterday',
            'priority' => 'Medium',
            'attachments' => [],
            'history' => [
                ['at' => 'Jul 27, 14:32', 'label' => 'Request submitted', 'status' => 'Open'],
                ['at' => 'Jul 27, 14:35', 'label' => 'Assigned to KYC Team', 'status' => 'In Progress'],
                ['at' => 'Jul 27, 16:10', 'label' => 'KYC Team asked for a clearer scan', 'status' => 'Awaiting User'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 27, 14:32', 'body' => 'The passport upload keeps failing with an error. I have tried both PNG and PDF formats.'],
                ['from' => 'support', 'at' => 'Jul 27, 16:10', 'body' => 'Apologies for the trouble! Please make sure the file is under 10 MB and the scan is clear with all corners visible. A freshly taken photo usually works best.'],
            ],
        ],
        [
            'reference' => 'AVC-SUP-2026-008416',
            'subject' => 'Questions about Luxury Villas project returns',
            'category' => 'Project Investment',
            'status' => 'open',
            'lastUpdate' => '2 days ago',
            'priority' => 'Low',
            'attachments' => [],
            'history' => [
                ['at' => 'Jul 26, 09:00', 'label' => 'Request submitted', 'status' => 'Open'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 26, 09:00', 'body' => 'I would like to know how the expected return for the Luxury Villas project is calculated and when earnings are paid out each cycle.'],
            ],
        ],
        [
            'reference' => 'AVC-SUP-2026-008410',
            'subject' => 'Duplicate commission credited to my account',
            'category' => 'Affiliate Support',
            'status' => 'escalated',
            'lastUpdate' => '3 days ago',
            'priority' => 'High',
            'attachments' => ['commission_statement.pdf'],
            'history' => [
                ['at' => 'Jul 24, 18:22', 'label' => 'Request submitted', 'status' => 'Open'],
                ['at' => 'Jul 24, 18:45', 'label' => 'Assigned to Affiliate Team', 'status' => 'In Progress'],
                ['at' => 'Jul 25, 10:00', 'label' => 'Escalated to Finance Supervisor', 'status' => 'Escalated'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 24, 18:22', 'body' => 'A commission for the same referral appears to have been credited twice. Could you review and correct this?'],
                ['from' => 'support', 'at' => 'Jul 25, 10:00', 'body' => 'Thank you for reporting this. We escalated your case to the Finance Supervisor for a full review. You will hear from us within 24 hours.'],
            ],
        ],
        [
            'reference' => 'AVC-SUP-2026-008402',
            'subject' => 'Bank transfer deposit confirmation',
            'category' => 'Deposit Support',
            'status' => 'resolved',
            'lastUpdate' => 'Jul 28',
            'priority' => 'Medium',
            'attachments' => [],
            'history' => [
                ['at' => 'Jul 22, 12:00', 'label' => 'Request submitted', 'status' => 'Open'],
                ['at' => 'Jul 22, 12:10', 'label' => 'Assigned to Finance Team', 'status' => 'In Progress'],
                ['at' => 'Jul 22, 15:30', 'label' => 'Deposit confirmed and AVC credited', 'status' => 'Resolved'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 22, 12:00', 'body' => 'I sent a bank transfer for my deposit (DEP-2026-000101) but it has not been confirmed yet.'],
                ['from' => 'support', 'at' => 'Jul 22, 15:30', 'body' => 'All set! Your deposit was confirmed and the AVC has been credited to your wallet. Thank you for your patience.'],
            ],
        ],
        [
            'reference' => 'AVC-SUP-2026-008395',
            'subject' => 'Update preferred contact method',
            'category' => 'Account & Profile',
            'status' => 'closed',
            'lastUpdate' => 'Jul 20',
            'priority' => 'Low',
            'attachments' => [],
            'history' => [
                ['at' => 'Jul 18, 09:30', 'label' => 'Request submitted', 'status' => 'Open'],
                ['at' => 'Jul 18, 09:40', 'label' => 'Contact method updated', 'status' => 'Resolved'],
                ['at' => 'Jul 18, 09:41', 'label' => 'Request closed', 'status' => 'Closed'],
            ],
            'messages' => [
                ['from' => 'user', 'at' => 'Jul 18, 09:30', 'body' => 'Please update my preferred contact method to WhatsApp.'],
                ['from' => 'support', 'at' => 'Jul 18, 09:40', 'body' => 'Done! Your preferred contact method is now WhatsApp.'],
            ],
        ],
    ];

    protected array $articles = [
        ['title' => 'How do I deposit AVC into my account?', 'excerpt' => 'Step-by-step guide to funding your wallet through Bank Transfer, Card, Wire or Crypto.', 'category' => 'Deposits'],
        ['title' => 'Why is my withdrawal under review?', 'excerpt' => 'Learn about the verification steps and processing times for withdrawals.', 'category' => 'Withdrawals'],
        ['title' => 'How long does KYC verification take?', 'excerpt' => 'Typical review times and what to do if your documents need resubmission.', 'category' => 'KYC'],
        ['title' => 'How do project earnings work?', 'excerpt' => 'Understanding share cycles, activation thresholds and payout schedules.', 'category' => 'Projects'],
        ['title' => 'What is an AVC credit swap?', 'excerpt' => 'Everything you need to know about buying and selling AVC on the marketplace.', 'category' => 'Marketplace'],
        ['title' => 'How do affiliate commissions work?', 'excerpt' => 'Commission rates, eligibility and how payouts are processed.', 'category' => 'Affiliate'],
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
            'stats' => [
                ['label' => 'Open Requests', 'value' => 3, 'caption' => 'Needs attention', 'icon' => 'heroicon-o-inbox', 'color' => 'bg-blue-500'],
                ['label' => 'Awaiting User Response', 'value' => 2, 'caption' => 'Your action needed', 'icon' => 'heroicon-o-clock', 'color' => 'bg-amber-500'],
                ['label' => 'Resolved Requests', 'value' => 28, 'caption' => 'All time', 'icon' => 'heroicon-o-check-circle', 'color' => 'bg-emerald-500'],
                ['label' => 'Average Response Time', 'value' => '4h 32m', 'caption' => 'Last 30 days', 'icon' => 'heroicon-o-bolt', 'color' => 'bg-violet-500'],
            ],
            'accountManager' => [
                'name' => 'James Carter',
                'initials' => 'JC',
                'department' => 'Client Success & Finance Support',
                'availability' => 'Available now',
                'workingHours' => 'Mon – Fri · 9:00 AM – 6:00 PM (GMT+1)',
                'languages' => 'English, French, Spanish',
            ],
            'categories' => $this->categories,
            'requests' => $this->requests,
            'articles' => $this->articles,
            'contact' => [
                'email' => 'support@avcrealestate.com',
                'phone' => '+1 800 555 0134',
                'whatsapp' => 'https://wa.me/18005550134',
                'telegram' => 'https://t.me/avc_support',
                'liveSupportHours' => '24/7 live support',
            ],
        ];

        return view('support.index', $data);
    }

    public function show(string $reference)
    {
        $user = Auth::user();
        $request = collect($this->requests)->firstWhere('reference', $reference);

        if (! $request) {
            abort(404);
        }

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'supportRequest' => $request,
            'accountManager' => [
                'name' => 'James Carter',
                'initials' => 'JC',
                'department' => 'Client Success & Finance Support',
                'availability' => 'Available now',
                'workingHours' => 'Mon – Fri · 9:00 AM – 6:00 PM (GMT+1)',
                'languages' => 'English, French, Spanish',
            ],
        ];

        return view('support.show', $data);
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
