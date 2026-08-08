<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectEarningsController extends Controller
{
    protected array $cycles = [
        ['key' => 'green-city', 'title' => 'Green City Apartments', 'location' => 'Manila, Philippines', 'flag' => '🇵🇭', 'gradient' => 'from-emerald-700 via-green-700 to-teal-900', 'shares' => 40, 'today' => 2.35, 'total' => 320.40, 'lastCredited' => 'Aug 08, 2026 · 09:00'],
        ['key' => 'ocean-view', 'title' => 'Ocean View Residences', 'location' => 'Miami, United States', 'flag' => '🇺🇸', 'gradient' => 'from-blue-700 via-cyan-700 to-blue-900', 'shares' => 25, 'today' => 1.15, 'total' => 210.75, 'lastCredited' => 'Aug 08, 2026 · 09:00'],
        ['key' => 'skyline', 'title' => 'Skyline Offices', 'location' => 'Dubai, UAE', 'flag' => '🇦🇪', 'gradient' => 'from-slate-700 via-blue-800 to-indigo-900', 'shares' => 15, 'today' => 0.85, 'total' => 145.20, 'lastCredited' => 'Aug 08, 2026 · 09:00'],
    ];

    protected array $history = [
        ['date' => 'Aug 08, 2026 · 09:00', 'project' => 'Green City Apartments', 'amount' => 2.35, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 08, 2026 · 09:00', 'project' => 'Ocean View Residences', 'amount' => 1.15, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 08, 2026 · 09:00', 'project' => 'Skyline Offices', 'amount' => 0.85, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 07, 2026 · 09:00', 'project' => 'Green City Apartments', 'amount' => 2.35, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 07, 2026 · 09:00', 'project' => 'Ocean View Residences', 'amount' => 1.15, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 06, 2026 · 09:00', 'project' => 'Green City Apartments', 'amount' => 2.35, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 06, 2026 · 09:00', 'project' => 'Skyline Offices', 'amount' => 0.85, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
        ['date' => 'Aug 05, 2026 · 09:00', 'project' => 'Ocean View Residences', 'amount' => 1.15, 'destination' => 'AVC Wallet', 'status' => 'Credited'],
    ];

    public function index()
    {
        $user = Auth::user();

        $filter = request()->query('project');
        $cycles = $this->cycles;
        $history = $this->history;
        $filteredTitle = null;

        if ($filter) {
            $matched = collect($cycles)->first(fn ($cycle) => $cycle['key'] === $filter);
            if (! $matched) {
                $real = Project::where('uuid', $filter)->first();
                $matched = $real
                    ? collect($cycles)->first(fn ($cycle) => strtolower($cycle['title']) === strtolower($real->title))
                    : null;
            }

            if ($matched) {
                $cycles = [$matched];
                $history = collect($history)->where('project', $matched['title'])->values()->all();
                $filteredTitle = $matched['title'];
            }
        }

        $balance = (float) ($user->wallet_balance ?? 0);
        if ($balance <= 0) {
            $balance = 500;
        }

        $todayTotal = array_sum(array_column($cycles, 'today'));

        $data = [
            'user' => $user,
            'profile' => [
                'name' => $user->name ?? 'new',
                'initials' => $this->initials($user->name ?? 'new'),
            ],
            'filteredTitle' => $filteredTitle,
            'summary' => [
                ['label' => 'Total Earnings', 'value' => number_format(8540.50, 2) . ' AVC', 'caption' => '≈ $8,540.50 USD', 'icon' => 'heroicon-o-banknotes', 'color' => 'bg-blue-500'],
                ['label' => 'Available Earnings', 'value' => number_format($balance, 2) . ' AVC', 'caption' => 'In your AVC Wallet', 'icon' => 'heroicon-o-wallet', 'color' => 'bg-emerald-500'],
                ['label' => "Today's Earnings", 'value' => number_format($todayTotal, 2) . ' AVC', 'caption' => 'Credited today', 'icon' => 'heroicon-o-sun', 'color' => 'bg-amber-500'],
                ['label' => 'Active Cycles', 'value' => count($cycles), 'caption' => 'Currently earning', 'icon' => 'heroicon-o-arrow-trending-up', 'color' => 'bg-violet-500'],
            ],
            'todayEarnings' => collect($cycles)->map(function ($cycle) {
                return [
                    'title' => $cycle['title'],
                    'flag' => $cycle['flag'],
                    'gradient' => $cycle['gradient'],
                    'amount' => $cycle['today'],
                    'time' => '09:00 AM',
                    'status' => 'Credited',
                ];
            })->values()->all(),
            'todayTotal' => $todayTotal,
            'cycles' => $cycles,
            'history' => $history,
        ];

        return view('project-earnings.index', $data);
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
