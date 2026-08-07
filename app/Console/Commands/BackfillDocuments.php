<?php

namespace App\Console\Commands;

use App\Models\CreditSwap;
use App\Models\Deposit;
use App\Models\FinanceRequest;
use App\Models\ProjectShareCycle;
use App\Models\ProjectUpdate;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\DocumentService;
use Illuminate\Console\Command;

class BackfillDocuments extends Command
{
    protected $signature = 'documents:backfill {--dry-run : Report what would be generated without creating anything}';

    protected $description = 'Generate PDF documents for all existing platform records (idempotent)';

    public function handle(DocumentService $documents): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $count = 0;

        $count += $this->deposits($documents, $dryRun);
        $count += $this->withdrawals($documents, $dryRun);
        $count += $this->cycles($documents, $dryRun);
        $count += $this->purchases($documents, $dryRun);
        $count += $this->creditSwaps($documents, $dryRun);
        $count += $this->financeRequests($documents, $dryRun);
        $count += $this->kyc($documents, $dryRun);
        $count += $this->projectUpdates($documents, $dryRun);

        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "Done. {$count} document(s) generated or already present.");

        return self::SUCCESS;
    }

    protected function deposits(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (Deposit::where('status', 'avc_credited')->orWhere('status', 'confirmed')->get() as $deposit) {
            if ($dryRun) { $count++; continue; }
            $documents->generate('deposit_receipt', $deposit, $deposit->user, ['metadata' => ['related_label' => $deposit->deposit_code]]) && $count++;
        }

        return $count;
    }

    protected function withdrawals(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (Withdrawal::where('status', 'completed')->get() as $withdrawal) {
            if ($dryRun) { $count++; continue; }
            $documents->generate('withdrawal_confirmation', $withdrawal, $withdrawal->user, ['metadata' => ['related_label' => $withdrawal->withdrawal_code]]) && $count++;
        }
        foreach (Withdrawal::whereNotIn('status', ['completed', 'rejected', 'cancelled', 'failed', 'returned'])->get() as $withdrawal) {
            if ($dryRun) { $count++; continue; }
            $documents->generate('withdrawal_request_receipt', $withdrawal, $withdrawal->user, ['metadata' => ['related_label' => $withdrawal->withdrawal_code]]) && $count++;
        }

        return $count;
    }

    protected function cycles(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (ProjectShareCycle::with('project')->get() as $cycle) {
            $meta = ['metadata' => ['related_label' => \App\Support\DocumentTypes::relatedLabel($cycle)]];
            if ($dryRun) { $count += 3; continue; }
            $documents->generate('cycle_receipt', $cycle, $cycle->user, $meta) && $count++;
            $documents->generate('investment_agreement', $cycle, $cycle->user, $meta) && $count++;
            $documents->generate('share_certificate', $cycle, $cycle->user, $meta) && $count++;
            if ($cycle->status === 'active') {
                $documents->generate('ownership_certificate', $cycle, $cycle->user, $meta) && $count++;
            }
        }

        return $count;
    }

    protected function purchases(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (Purchase::with('property')->get() as $purchase) {
            $meta = ['metadata' => ['related_label' => $purchase->property->title . ' (' . $purchase->property->ref() . ')']];
            if ($dryRun) { $count += 2; continue; }
            $documents->generate('property_contract', $purchase, $purchase->user, $meta) && $count++;
            $documents->generate('property_receipt', $purchase, $purchase->user, $meta) && $count++;
        }

        return $count;
    }

    protected function creditSwaps(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (CreditSwap::with('seller')->whereNotNull('listing_number')->get() as $swap) {
            if (! $swap->seller) {
                continue;
            }
            $meta = ['metadata' => ['related_label' => $swap->listingLabel()]];
            $orderType = $swap->offer_type === 'buy' ? 'buy_order_receipt' : 'sell_order_receipt';
            if ($dryRun) {
                $count += 2;
                continue;
            }
            $documents->generate($orderType, $swap, $swap->seller, $meta) && $count++;
            $documents->generate('escrow_agreement', $swap, $swap->seller, $meta) && $count++;
            if ($swap->status === 'completed') {
                if ($swap->buyer) {
                    $documents->generate('escrow_completion_certificate', $swap, $swap->buyer, $meta) && $count++;
                }
                $documents->generate('escrow_completion_certificate', $swap, $swap->seller, $meta) && $count++;
            }
        }

        return $count;
    }

    protected function financeRequests(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (FinanceRequest::where('status', 'completed')->get() as $request) {
            if ($dryRun) { $count++; continue; }
            $documents->generate('finance_request_receipt', $request, $request->user, ['metadata' => ['related_label' => $request->request_id]]) && $count++;
        }

        return $count;
    }

    protected function kyc(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (User::where('kyc_status', 'approved')->get() as $user) {
            if ($dryRun) {
                $count += 3;
                continue;
            }
            $documents->generate('kyc_verification_certificate', null, $user, ['metadata' => ['related_label' => 'Personal']]) && $count++;
            if ($user->kyc_document_path) {
                $documents->registerExternal('identity_report', $user, 'Identity Document', $user->kyc_document_path, ['related_label' => 'KYC Identity Document'], 'verified') && $count++;
            }
            if ($user->kyc_selfie_path) {
                $documents->registerExternal('identity_report', $user, 'Identity Selfie', $user->kyc_selfie_path, ['related_label' => 'KYC Identity Selfie'], 'verified') && $count++;
            }
        }

        return $count;
    }

    protected function projectUpdates(DocumentService $documents, bool $dryRun): int
    {
        $count = 0;
        foreach (ProjectUpdate::with('project')->get() as $update) {
            if (! $update->project) {
                continue;
            }
            $meta = ['metadata' => ['related_label' => $update->project->title]];
            $userIds = $update->project->shareCycles()
                ->whereIn('status', ['pending_activation', 'active', 'completed'])
                ->distinct()
                ->pluck('user_id');
            $shareholders = User::whereIn('id', $userIds)->get();

            if ($dryRun) {
                $count += max(1, $shareholders->count());
                continue;
            }
            foreach ($shareholders as $shareholder) {
                $documents->generate('project_update', $update, $shareholder, $meta) && $count++;
            }
        }

        return $count;
    }
}
