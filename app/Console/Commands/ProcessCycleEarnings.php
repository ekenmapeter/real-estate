<?php

namespace App\Console\Commands;

use App\Models\ProjectShareCycle;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessCycleEarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:process-earnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process completed project share earning cycles and credit principal + ROI to user AVC/wallet balance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for matured active project share cycles...');

        $maturedCycles = ProjectShareCycle::with(['project', 'user'])
            ->where('status', 'active')
            ->whereNotNull('completion_date')
            ->where('completion_date', '<=', now())
            ->get();

        if ($maturedCycles->isEmpty()) {
            $this->info('No matured share cycles found at this time.');
            return 0;
        }

        $processedCount = 0;
        $errorCount = 0;

        foreach ($maturedCycles as $cycle) {
            DB::beginTransaction();
            try {
                $user = $cycle->user;
                if (!$user) {
                    DB::rollBack();
                    $this->warn("Skipping cycle {$cycle->cycle_code}: User not found.");
                    continue;
                }

                $creditAmount = (float) $cycle->completion_value;

                // Credit directly to user's wallet_balance
                $user->wallet_balance = round((float) $user->wallet_balance + $creditAmount, 2);
                $user->save();

                // Update cycle state
                $cycle->status = 'completed';
                $cycle->earnings_credited_at = now();
                $cycle->save();

                // Record transaction
                $projectTitle = $cycle->project->title ?? 'Project Share';
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'completion_credit',
                    'amount' => $creditAmount,
                    'reference' => 'CRD-' . $cycle->cycle_code,
                    'description' => 'Earning cycle completed (' . $cycle->duration_label . '). Credited ' . number_format($creditAmount, 2) . ' AVC (principal + ROI) for ' . $projectTitle,
                    'status' => 'completed',
                ]);

                DB::commit();
                $processedCount++;

                $this->info("✓ Credited {$creditAmount} AVC to {$user->name} (#{$user->id}) for cycle {$cycle->cycle_code}");
                Log::info("Share Cycle Completed: {$cycle->cycle_code} — User #{$user->id} received {$creditAmount} AVC.");

            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                $this->error("✗ Error processing cycle {$cycle->cycle_code}: " . $e->getMessage());
                Log::error("Error processing share cycle {$cycle->cycle_code}: " . $e->getMessage());
            }
        }

        $this->info("Done. Processed: {$processedCount} | Errors: {$errorCount}");
        return 0;
    }
}
