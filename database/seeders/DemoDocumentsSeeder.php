<?php

namespace Database\Seeders;

use App\Models\CreditSwap;
use App\Models\Deposit;
use App\Models\Project;
use App\Models\ProjectShareCycle;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\DocumentService;
use Illuminate\Database\Seeder;

class DemoDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $documents = app(DocumentService::class);
        $project = Project::first();

        $targets = User::where('role', '!=', 'admin')->get()
            ->filter(fn (User $u) => $u->documents()->count() === 0);

        $created = 0;
        foreach ($targets as $user) {
            $created += $this->seedUser($documents, $user, $project);
        }

        $this->command?->info("Demo documents seeded for {$targets->count()} account(s).");
    }

    protected function seedUser(DocumentService $documents, User $user, ?Project $project): int
    {
        $count = 0;

        // 1. Demo deposit (credited) → Deposit Receipt + Deposit Confirmation
        $deposit = Deposit::where('user_id', $user->id)->where('status', 'avc_credited')->first()
            ?? Deposit::create([
                'user_id' => $user->id,
                'deposit_code' => 'DEP-DEMO-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                'deposit_type' => 'avc_purchase',
                'payment_method' => 'bank_transfer',
                'amount' => 1500.00,
                'deposit_amount' => 1500.00,
                'deposit_currency' => 'USD',
                'base_usd_value' => 1500.00,
                'avc_rate' => 1.0000,
                'gross_avc' => 1500.00,
                'fee_amount' => 0.00,
                'net_avc' => 1500.00,
                'status' => 'avc_credited',
                'credited_at' => now()->subDays(21),
            ]);
        $documents->generate('deposit_receipt', $deposit, $user, ['metadata' => ['related_label' => $deposit->deposit_code]]) && $count++;
        $documents->generate('deposit_confirmation', $deposit, $user, ['metadata' => ['related_label' => $deposit->deposit_code]]) && $count++;

        // 2. Demo withdrawal (completed) → Withdrawal Request Receipt + Confirmation
        $withdrawal = Withdrawal::where('user_id', $user->id)->where('status', 'completed')->first()
            ?? Withdrawal::create([
                'user_id' => $user->id,
                'withdrawal_code' => 'WDR-DEMO-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                'withdrawal_method' => 'bank_transfer',
                'amount' => 800.00,
                'avc_amount' => 800.00,
                'gross_usd_value' => 800.00,
                'platform_fee' => 8.00,
                'estimated_net_payout' => 792.00,
                'payout_currency' => 'USD',
                'status' => 'completed',
                'processed_at' => now()->subDays(14),
                'completed_at' => now()->subDays(12),
            ]);
        $documents->generate('withdrawal_request_receipt', $withdrawal, $user, ['metadata' => ['related_label' => $withdrawal->withdrawal_code]]) && $count++;
        $documents->generate('withdrawal_confirmation', $withdrawal, $user, ['metadata' => ['related_label' => $withdrawal->withdrawal_code]]) && $count++;

        // 3. Demo share cycle → Investment Agreement, Share Certificate, Cycle Receipt, Ownership Certificate
        if ($project) {
            $cycle = ProjectShareCycle::where('user_id', $user->id)->first()
                ?? ProjectShareCycle::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'cycle_code' => 'CYC-DEMO-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                    'duration_key' => '3_months',
                    'duration_label' => '3 Months',
                    'duration_days' => 90,
                    'shares_owned' => 10,
                    'required_shares' => 10,
                    'share_price' => 100.00,
                    'total_purchase_amount' => 1000.00,
                    'target_earnings_pct' => 8.00,
                    'projected_earnings' => 80.00,
                    'completion_value' => 1080.00,
                    'status' => 'active',
                    'purchased_at' => now()->subDays(30),
                    'activated_at' => now()->subDays(30),
                    'completion_date' => now()->addDays(60),
                    'receipt_number' => 'RCP-DEMO-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                ]);

            $meta = ['metadata' => ['related_label' => \App\Support\DocumentTypes::relatedLabel($cycle)]];
            $documents->generate('cycle_receipt', $cycle, $user, $meta) && $count++;
            $documents->generate('investment_agreement', $cycle, $user, $meta) && $count++;
            $documents->generate('share_certificate', $cycle, $user, $meta) && $count++;
            $documents->generate('ownership_certificate', $cycle, $user, $meta) && $count++;
        }

        // 4. Demo marketplace trade → Buy Order Receipt, Escrow Agreement, Escrow Completion Certificate
        $swap = CreditSwap::where('user_id', $user->id)->first()
            ?? CreditSwap::create([
                'user_id' => $user->id,
                'seller_id' => $user->id,
                'offer_type' => 'buy',
                'amount' => 500.00,
                'payment_method' => 'bank_transfer',
                'payment_details' => 'Demo bank account •••• 0042',
                'country' => 'United States',
                'reference' => 'DEMO-' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
                'listing_number' => str_pad((string) ($user->id * 100), 4, '0', STR_PAD_LEFT),
                'status' => 'completed',
            ]);
        $documents->generate('buy_order_receipt', $swap, $user, ['metadata' => ['related_label' => $swap->listingLabel()]]) && $count++;
        $documents->generate('escrow_agreement', $swap, $user, ['metadata' => ['related_label' => $swap->listingLabel()]]) && $count++;
        $documents->generate('escrow_completion_certificate', $swap, $user, ['metadata' => ['related_label' => $swap->listingLabel()]]) && $count++;

        // 5. KYC verification certificate
        if ($user->kyc_status === 'approved') {
            $documents->generate('kyc_verification_certificate', null, $user, ['metadata' => ['related_label' => 'Personal']]) && $count++;
        }

        // 6. Monthly statement
        $documents->monthlyStatement($user, now()->subMonth()->format('Y-m')) && $count++;

        return $count;
    }
}
