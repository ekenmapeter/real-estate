<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectShareCycle;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SharePurchaseController extends Controller
{
    /**
     * Calculate purchase calculations in real time for JS calculator.
     */
    public function calculate(Request $request, Project $project)
    {
        $request->validate([
            'duration_key' => 'required|string',
            'shares' => 'required|integer|min:1',
        ]);

        $durationKey = $request->duration_key;
        $shares = (int) $request->shares;

        $tiers = $project->getAvailableTiers();
        $selectedTier = collect($tiers)->firstWhere('duration_key', $durationKey);
        if (!$selectedTier) {
            $selectedTier = $tiers->first() ?? $tiers[0];
        }

        $sharePrice = (float) ($project->share_price ?: 100.00);
        $purchaseAmount = round($shares * $sharePrice, 2);
        $targetEarningsPct = (float) $selectedTier->target_earnings_pct;
        $projectedEarnings = round(($purchaseAmount * $targetEarningsPct) / 100, 2);
        $completionValue = round($purchaseAmount + $projectedEarnings, 2);

        $user = Auth::user();
        $userBalance = $user ? (float) $user->wallet_balance : 0;
        $remainingBalance = max(0, round($userBalance - $purchaseAmount, 2));

        $requiredShares = (int) $selectedTier->required_shares;
        $activationStatus = $shares >= $requiredShares ? 'Active' : 'Pending Activation';
        $completionDate = now()->addDays((int) $selectedTier->duration_days)->format('M d, Y');

        return response()->json([
            'shares' => $shares,
            'share_price' => $sharePrice,
            'purchase_amount' => $purchaseAmount,
            'target_earnings_pct' => $targetEarningsPct,
            'projected_earnings' => $projectedEarnings,
            'completion_value' => $completionValue,
            'required_shares' => $requiredShares,
            'activation_status' => $activationStatus,
            'completion_date' => $completionDate,
            'user_balance' => $userBalance,
            'remaining_balance' => $remainingBalance,
            'has_sufficient_balance' => $userBalance >= $purchaseAmount,
        ]);
    }

    /**
     * Store share purchase transaction.
     */
    public function store(Request $request, Project $project)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to purchase project shares.');
        }

        $request->validate([
            'duration_key' => 'required|string',
            'shares' => 'required|integer|min:1',
            'security_pin' => 'nullable|string',
        ]);

        if ($project->status !== 'active') {
            return redirect()->back()->with('error', 'This project is no longer open for new share purchases.');
        }

        // Check if project funding window is still open
        if (!$project->isActiveWindow()) {
            return redirect()->back()->with('error', 'The funding window for this project has closed.');
        }

        $durationKey = $request->duration_key;
        $shares = (int) $request->shares;

        $tiers = $project->getAvailableTiers();
        $selectedTier = collect($tiers)->firstWhere('duration_key', $durationKey);

        if (!$selectedTier) {
            return redirect()->back()->with('error', 'Invalid earning duration selected.');
        }

        $sharePrice = (float) ($project->share_price ?: 100.00);
        $purchaseAmount = round($shares * $sharePrice, 2);

        // Check user wallet balance
        $userBalance = (float) $user->wallet_balance;
        if ($userBalance < $purchaseAmount) {
            return redirect()->back()->with('error', 'Insufficient AVC Balance. You need ' . number_format($purchaseAmount, 2) . ' AVC, but your current balance is ' . number_format($userBalance, 2) . ' AVC.');
        }

        DB::beginTransaction();
        try {
            // Deduct from wallet_balance
            $user->wallet_balance = round($userBalance - $purchaseAmount, 2);
            $user->save();

            // Check if user has an existing pending_activation cycle for this project & duration to top up
            $existingPending = ProjectShareCycle::where('user_id', $user->id)
                ->where('project_id', $project->id)
                ->where('duration_key', $durationKey)
                ->where('status', 'pending_activation')
                ->first();

            if ($existingPending) {
                // Top up existing pending cycle
                $existingPending->shares_owned += $shares;
                $existingPending->total_purchase_amount = round((float) $existingPending->total_purchase_amount + $purchaseAmount, 2);

                $targetPct = (float) $existingPending->target_earnings_pct;
                $existingPending->projected_earnings = round(((float) $existingPending->total_purchase_amount * $targetPct) / 100, 2);
                $existingPending->completion_value = round((float) $existingPending->total_purchase_amount + (float) $existingPending->projected_earnings, 2);

                // Check if threshold now met
                if ($existingPending->shares_owned >= $existingPending->required_shares) {
                    $existingPending->status = 'active';
                    $existingPending->activated_at = now();
                    $existingPending->completion_date = now()->addDays($existingPending->duration_days);
                }

                $existingPending->save();
                $cycle = $existingPending;
                $txType = 'additional_share_purchase';
                $txDesc = 'Top-up: ' . $shares . ' shares (' . number_format($purchaseAmount, 2) . ' AVC) for ' . $project->title;
            } else {
                // Create brand new cycle
                $requiredShares = (int) $selectedTier->required_shares;
                $targetPct = (float) $selectedTier->target_earnings_pct;
                $durationDays = (int) $selectedTier->duration_days;
                $durationLabel = $selectedTier->duration_label;

                $projectedEarnings = round(($purchaseAmount * $targetPct) / 100, 2);
                $completionValue = round($purchaseAmount + $projectedEarnings, 2);

                $isActivated = $shares >= $requiredShares;
                $status = $isActivated ? 'active' : 'pending_activation';
                $activatedAt = $isActivated ? now() : null;
                $completionDate = $isActivated ? now()->addDays($durationDays) : null;

                $cycle = ProjectShareCycle::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'duration_key' => $durationKey,
                    'duration_label' => $durationLabel,
                    'duration_days' => $durationDays,
                    'shares_owned' => $shares,
                    'required_shares' => $requiredShares,
                    'share_price' => $sharePrice,
                    'total_purchase_amount' => $purchaseAmount,
                    'target_earnings_pct' => $targetPct,
                    'projected_earnings' => $projectedEarnings,
                    'completion_value' => $completionValue,
                    'status' => $status,
                    'purchased_at' => now(),
                    'activated_at' => $activatedAt,
                    'completion_date' => $completionDate,
                ]);

                $txType = 'project_share_purchase';
                $txDesc = 'Purchased ' . $shares . ' shares (' . number_format($purchaseAmount, 2) . ' AVC) in ' . $project->title;
            }

            // Create Transaction entry
            Transaction::create([
                'user_id' => $user->id,
                'type' => $txType,
                'amount' => $purchaseAmount,
                'reference' => $cycle->cycle_code,
                'description' => $txDesc,
                'status' => 'completed',
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shares purchased successfully!',
                    'cycle' => $cycle->fresh(),
                    'redirect' => route('portfolio.index'),
                ]);
            }

            $successMsg = $cycle->status === 'active'
                ? '🎉 Success! You purchased ' . $shares . ' shares in ' . $project->title . '. Your ' . $cycle->duration_label . ' earning cycle is now ACTIVE!'
                : '✅ You purchased ' . $shares . ' shares in ' . $project->title . '. Buy ' . $cycle->remainingSharesNeeded() . ' more shares to activate your ' . $cycle->duration_label . ' earning cycle.';

            return redirect()->route('portfolio.index')->with('success', $successMsg);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Purchase failed. Please try again or contact support.');
        }
    }
}
