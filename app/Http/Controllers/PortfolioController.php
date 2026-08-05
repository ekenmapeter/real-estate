<?php

namespace App\Http\Controllers;

use App\Models\ProjectShareCycle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * Display My Portfolio page with tabbed cycles and metrics summary.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $allCycles = ProjectShareCycle::with(['project.images'])
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $activeCycles = $allCycles->where('status', 'active');
        $pendingCycles = $allCycles->where('status', 'pending_activation');
        $completedCycles = $allCycles->where('status', 'completed');

        $totalShareValue = $allCycles->sum('total_purchase_amount');
        $activeShareValue = $activeCycles->sum('total_purchase_amount');
        $pendingShareValue = $pendingCycles->sum('total_purchase_amount');
        $projectedEarnings = $activeCycles->sum('projected_earnings');
        $earningsReceived = $completedCycles->sum('projected_earnings');
        $activeProjectsCount = $activeCycles->pluck('project_id')->unique()->count();

        // Project Updates for projects owned by user
        $ownedProjectIds = $allCycles->pluck('project_id')->unique();
        $projectUpdates = \App\Models\ProjectUpdate::with('project')
            ->whereIn('project_id', $ownedProjectIds)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('portfolio.index', compact(
            'user',
            'activeCycles',
            'pendingCycles',
            'completedCycles',
            'totalShareValue',
            'activeShareValue',
            'pendingShareValue',
            'projectedEarnings',
            'earningsReceived',
            'activeProjectsCount',
            'projectUpdates'
        ));
    }

    /**
     * Download or view printable cycle receipt.
     */
    public function downloadReceipt(ProjectShareCycle $cycle)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || $cycle->user_id !== $user->id) {
            abort(403, 'Unauthorized access to cycle receipt.');
        }

        $cycle->load(['project', 'user']);

        return view('portfolio.receipt', compact('cycle'));
    }
}
