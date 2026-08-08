<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransferController extends Controller
{
    protected array $recipients = [
        ['avcId' => 'RDR-884901', 'name' => 'Sarah Jenkins', 'email' => 'sarah@example.com', 'verified' => true],
        ['avcId' => 'AVC-119032', 'name' => 'Michael Osei', 'email' => 'michael@example.com', 'verified' => true],
        ['avcId' => 'AVC-204877', 'name' => 'Priya Sharma', 'email' => 'priya@example.com', 'verified' => true],
        ['avcId' => 'AVC-881230', 'name' => 'Daniel Kim', 'email' => 'daniel@example.com', 'verified' => true],
        ['avcId' => 'AVC-550011', 'name' => 'Unverified Member', 'email' => 'member@example.com', 'verified' => false],
    ];

    protected array $transfers = [
        [
            'id' => 'TRF-2026-000482', 'type' => 'sent', 'status' => 'completed',
            'counterparty' => 'Sarah Jenkins', 'counterpartyId' => 'RDR-884901',
            'counterpartyEmail' => 'sarah@example.com', 'amount' => 500.00, 'fee' => 0.00,
            'date' => 'Jul 29, 2026 · 14:32', 'note' => 'Monthly rent share',
        ],
        [
            'id' => 'TRF-2026-000479', 'type' => 'received', 'status' => 'completed',
            'counterparty' => 'Michael Osei', 'counterpartyId' => 'AVC-119032',
            'counterpartyEmail' => 'michael@example.com', 'amount' => 250.00, 'fee' => 0.00,
            'date' => 'Jul 27, 2026 · 09:15', 'note' => 'Payment for shares',
        ],
        [
            'id' => 'TRF-2026-000475', 'type' => 'sent', 'status' => 'completed',
            'counterparty' => 'Priya Sharma', 'counterpartyId' => 'AVC-204877',
            'counterpartyEmail' => 'priya@example.com', 'amount' => 150.00, 'fee' => 0.00,
            'date' => 'Jul 25, 2026 · 18:40', 'note' => 'Split investment',
        ],
        [
            'id' => 'TRF-2026-000471', 'type' => 'received', 'status' => 'completed',
            'counterparty' => 'Daniel Kim', 'counterpartyId' => 'AVC-881230',
            'counterpartyEmail' => 'daniel@example.com', 'amount' => 300.00, 'fee' => 0.00,
            'date' => 'Jul 22, 2026 · 11:05', 'note' => 'Referral bonus transfer',
        ],
        [
            'id' => 'TRF-2026-000469', 'type' => 'sent', 'status' => 'pending',
            'counterparty' => 'Sarah Jenkins', 'counterpartyId' => 'RDR-884901',
            'counterpartyEmail' => 'sarah@example.com', 'amount' => 200.00, 'fee' => 0.00,
            'date' => 'Jul 20, 2026 · 16:22', 'note' => 'Awaiting PIN verification',
        ],
        [
            'id' => 'TRF-2026-000466', 'type' => 'sent', 'status' => 'failed',
            'counterparty' => 'Priya Sharma', 'counterpartyId' => 'AVC-204877',
            'counterpartyEmail' => 'priya@example.com', 'amount' => 75.00, 'fee' => 0.00,
            'date' => 'Jul 18, 2026 · 10:00', 'note' => 'Insufficient balance',
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        $balance = (float) ($user->wallet_balance ?? 0);
        if ($balance <= 0) {
            $balance = 500;
        }

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'summary' => [
                ['label' => 'Available AVC Balance', 'value' => number_format($balance) . ' AVC', 'caption' => '≈ $' . number_format($balance, 2) . ' USD', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-blue-500'],
                ['label' => 'Daily Transfer Limit', 'value' => '10,000 AVC', 'caption' => '2 / 10 used today', 'icon' => 'heroicon-o-arrow-trending-up', 'color' => 'bg-emerald-500'],
                ['label' => 'Monthly Transfer Usage', 'value' => '1,250 AVC', 'caption' => '12.5% of monthly limit', 'icon' => 'heroicon-o-chart-pie', 'color' => 'bg-violet-500'],
                ['label' => 'Transfer Fee', 'value' => '0 AVC', 'caption' => 'Internal transfers are free', 'icon' => 'heroicon-o-receipt-percent', 'color' => 'bg-orange-500'],
                ['label' => 'Transfers Today', 'value' => 2, 'caption' => '1 pending · 1 completed', 'icon' => 'heroicon-o-arrows-right-left', 'color' => 'bg-rose-500'],
            ],
            'transfers' => $this->transfers,
        ];

        return view('transfer.index', $data);
    }

    public function send()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'recipients' => $this->recipients,
        ];

        return view('transfer.send', $data);
    }

    public function receive()
    {
        $user = Auth::user();
        $accountId = $user->account_id ?? 'RDR-209311';
        $email = $user->email ?? 'kofiadjo09@gmail.com';
        $username = $user->name ?? 'new';

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $username,
                'initials' => $this->initials($username),
            ],
            'accountId' => $accountId,
            'email' => $email,
            'username' => $username,
            'qrSvg' => $this->qrCode(url('/transfer/receive?to=' . $accountId)),
        ];

        return view('transfer.receive', $data);
    }

    public function verifyPin(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['valid' => false, 'message' => 'Authentication required.'], 401);
        }

        $request->validate(['pin' => 'required|string|digits:4']);

        if ($user->transaction_pin && Hash::check($request->pin, $user->transaction_pin)) {
            return response()->json(['valid' => true]);
        }

        if ($user->transaction_pin) {
            return response()->json(['valid' => false, 'message' => 'Incorrect PIN. Please try again.']);
        }

        return response()->json([
            'valid' => false,
            'needs_pin' => true,
            'message' => 'Set up a transaction PIN in your Security settings before completing transfers.',
        ]);
    }

    public function show(string $transfer)
    {
        $user = Auth::user();
        $record = collect($this->transfers)->firstWhere('id', $transfer);

        if (! $record) {
            abort(404);
        }

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'transfer' => $record,
        ];

        return view('transfer.show', $data);
    }

    protected function qrCode(string $url): string
    {
        try {
            return QrCode::format('svg')->size(180)->margin(0)->generate($url);
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
