<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\ProjectInvestment;
use App\Models\Transaction;
use Illuminate\Console\Command;

class ProcessDailyRoi extends Command
{
    protected $signature = 'roi:process-daily';
    protected $description = 'Distribute daily ROI earnings to active investments';

    public function handle(): int
    {
        $processed = 0;
        $processed += $this->processPropertyInvestments();
        $processed += $this->processProjectInvestments();

        $this->info("Processed {$processed} investment(s) with daily ROI payouts.");
        return Command::SUCCESS;
    }

    private function processPropertyInvestments(): int
    {
        $investments = Investment::with(['property', 'user'])
            ->where('status', 'active')
            ->where('roi_earned', '<', \DB::raw('expected_roi_amount'))
            ->get();

        $processed = 0;
        $today = now()->format('Ymd');

        foreach ($investments as $investment) {
            $property = $investment->property;
            $user = $investment->user;

            if (!$property || !$user) {
                continue;
            }

            $ref = 'ROI-DAILY-' . $investment->id . '-' . $today;

            $alreadyPaid = Transaction::where('reference', $ref)
                ->where('type', 'roi_payout')
                ->exists();

            if ($alreadyPaid) {
                continue;
            }

            $totalInvestment = $investment->total_amount;
            $annualRoiRate = $property->roi_percentage / 100;
            $durationMonths = $property->investment_duration_months ?: 12;

            $totalExpectedRoi = $totalInvestment * $annualRoiRate;
            $dailyRoi = round($totalExpectedRoi / ($durationMonths * 30), 2);

            if ($dailyRoi <= 0) {
                continue;
            }

            $remainingRoi = $investment->expected_roi_amount - ($investment->roi_earned ?? 0);
            $payout = min($dailyRoi, $remainingRoi);

            if ($payout <= 0) {
                continue;
            }

            $investment->roi_earned = ($investment->roi_earned ?? 0) + $payout;
            $investment->save();

            $user->wallet_balance = ($user->wallet_balance ?? 0) + $payout;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'roi_payout',
                'amount' => $payout,
                'reference' => $ref,
                'description' => 'Daily ROI payout for ' . ($property->title ?? 'investment #' . $investment->id),
                'status' => 'completed',
            ]);

            if ($investment->roi_earned >= $investment->expected_roi_amount) {
                $investment->status = 'completed';
                $investment->save();

                $this->info("Investment #{$investment->id} completed — full ROI paid out.");
            }

            $processed++;
        }

        return $processed;
    }

    private function processProjectInvestments(): int
    {
        $investments = ProjectInvestment::with(['project', 'user'])
            ->where('status', 'active')
            ->where('roi_earned', '<', \DB::raw('expected_roi_amount'))
            ->get();

        $processed = 0;
        $today = now()->format('Ymd');

        foreach ($investments as $investment) {
            $project = $investment->project;
            $user = $investment->user;

            if (!$project || !$user) {
                continue;
            }

            $ref = 'ROI-PROJ-' . $investment->id . '-' . $today;

            $alreadyPaid = Transaction::where('reference', $ref)
                ->where('type', 'roi_payout')
                ->exists();

            if ($alreadyPaid) {
                continue;
            }

            $totalInvestment = $investment->amount;
            $annualRoiRate = $project->expected_return_percentage / 100;
            $durationMonths = $project->investment_duration_months ?: 12;

            $totalExpectedRoi = $totalInvestment * $annualRoiRate;
            $dailyRoi = round($totalExpectedRoi / ($durationMonths * 30), 2);

            if ($dailyRoi <= 0) {
                continue;
            }

            $remainingRoi = $investment->expected_roi_amount - ($investment->roi_earned ?? 0);
            $payout = min($dailyRoi, $remainingRoi);

            if ($payout <= 0) {
                continue;
            }

            $investment->roi_earned = ($investment->roi_earned ?? 0) + $payout;
            $investment->save();

            $user->wallet_balance = ($user->wallet_balance ?? 0) + $payout;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'roi_payout',
                'amount' => $payout,
                'reference' => $ref,
                'description' => 'Daily ROI payout for project ' . ($project->title ?? '#' . $investment->id),
                'status' => 'completed',
            ]);

            if ($investment->roi_earned >= $investment->expected_roi_amount) {
                $investment->status = 'completed';
                $investment->save();

                $this->info("Project investment #{$investment->id} completed — full ROI paid out.");
            }

            $processed++;
        }

        return $processed;
    }
}
