<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use App\Support\DocumentTypes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DocumentService
{
    protected const TYPE_CODES = [
        'investment_agreement' => 'INV',
        'share_certificate' => 'SHR',
        'ownership_certificate' => 'OWN',
        'project_update' => 'UPD',
        'cycle_receipt' => 'CYC',
        'property_contract' => 'PRP',
        'rental_agreement' => 'LSE',
        'property_receipt' => 'PRC',
        'deposit_receipt' => 'DEP',
        'deposit_confirmation' => 'DPC',
        'withdrawal_request_receipt' => 'WDR',
        'withdrawal_confirmation' => 'WDC',
        'finance_request_receipt' => 'FIN',
        'payment_confirmation' => 'PAY',
        'buy_order_receipt' => 'BUY',
        'sell_order_receipt' => 'SEL',
        'escrow_agreement' => 'ESC',
        'escrow_completion_certificate' => 'ESX',
        'kyc_verification_certificate' => 'KYC',
        'identity_report' => 'IDV',
        'monthly_statement' => 'STM',
        'annual_statement' => 'AST',
        'tax_summary' => 'TAX',
    ];

    /**
     * Generate a PDF document for a related record and register it.
     * Idempotent: skips when a document for (user, type, related) already exists.
     */
    public function generate(string $type, ?Model $related, User $user, array $overrides = [], bool $regenerate = false): ?Document
    {
        if (! DocumentTypes::isKnown($type)) {
            return null;
        }

        $existing = $this->findExisting($type, $related, $user->id);
        if ($existing && ! $regenerate) {
            return $existing;
        }

        try {
            $reference = $this->nextReference($type);
            $path = 'documents/' . $user->id . '/' . $reference . '.pdf';

            $title = $overrides['title'] ?? DocumentTypes::title($type, $related);
            $relatedLabel = $overrides['related_label'] ?? ($related ? DocumentTypes::relatedLabel($related) : null);
            $status = $overrides['status'] ?? DocumentTypes::defaultStatus($type);

            $pdf = Pdf::loadView(DocumentTypes::view($type), [
                'user' => $user,
                'related' => $related,
                'document' => (object) [
                    'title' => $title,
                    'reference' => $reference,
                    'category' => DocumentTypes::category($type),
                    'status' => $status,
                    'issued_at' => $overrides['issued_at'] ?? now(),
                    'related_label' => $relatedLabel,
                    'metadata' => $overrides['metadata'] ?? [],
                ],
                'metadata' => $overrides['metadata'] ?? [],
            ])->setPaper('a4', 'portrait');

            Storage::disk('public')->put($path, $pdf->output());

            $data = [
                'user_id' => $user->id,
                'category' => DocumentTypes::category($type),
                'document_type' => $type,
                'title' => $title,
                'reference' => $reference,
                'status' => $status,
                'file_path' => $path,
                'issued_at' => $overrides['issued_at'] ?? now(),
                'metadata' => array_merge([
                    'related_label' => $relatedLabel,
                    'related_type' => $related ? get_class($related) : null,
                    'related_ref' => $related && method_exists($related, 'ref') ? $related->ref() : null,
                ], $overrides['metadata'] ?? []),
            ];

            if ($related) {
                $data['related_type'] = $related->getMorphClass();
                $data['related_id'] = $related->getKey();
            }

            if ($existing) {
                $existing->update($data);
                $document = $existing;
            } else {
                $document = Document::create($data);
            }

            return $document;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Register an already-existing file (e.g. KYC upload) as a document record.
     */
    public function registerExternal(string $type, User $user, string $title, string $filePath, array $metadata = [], ?string $status = null): ?Document
    {
        if (! DocumentTypes::isKnown($type)) {
            return null;
        }

        $existing = Document::where('user_id', $user->id)
            ->where('document_type', $type)
            ->where('file_path', $filePath)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Document::create([
            'user_id' => $user->id,
            'category' => DocumentTypes::category($type),
            'document_type' => $type,
            'title' => $title,
            'reference' => $this->nextReference($type),
            'status' => $status ?? DocumentTypes::defaultStatus($type),
            'file_path' => $filePath,
            'issued_at' => now(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Generate (or fetch) the monthly statement for a user.
     */
    public function monthlyStatement(User $user, ?string $period = null): ?Document
    {
        $period = $period ?: now()->format('Y-m');
        [$year, $month] = array_map('intval', explode('-', $period));

        $existing = Document::where('user_id', $user->id)
            ->where('document_type', 'monthly_statement')
            ->where('metadata->period', $period)
            ->first();

        if ($existing) {
            return $existing;
        }

        $from = now()->create($year, $month, 1)->startOfMonth();
        $to = $from->copy()->endOfMonth();

        $transactions = $user->transactions()
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at')
            ->get();

        $deposits = $user->deposits()->where('status', 'approved')->whereBetween('created_at', [$from, $to])->get();
        $withdrawals = $user->withdrawals()->whereIn('status', ['approved', 'completed'])->whereBetween('created_at', [$from, $to])->get();

        return $this->generate('monthly_statement', null, $user, [
            'metadata' => [
                'period' => $period,
                'period_label' => $from->format('F Y'),
                'opening_balance' => 0,
                'closing_balance' => (float) $user->wallet_balance,
                'transaction_count' => $transactions->count(),
                'transactions' => $transactions->map(fn ($t) => [
                    'date' => $t->created_at->format('M d, Y'),
                    'type' => $t->type,
                    'description' => $t->description,
                    'reference' => $t->reference,
                    'amount' => (float) $t->amount,
                    'status' => $t->status,
                ])->all(),
                'deposit_count' => $deposits->count(),
                'withdrawal_count' => $withdrawals->count(),
            ],
            'title' => 'Monthly Finance Statement — ' . $from->format('F Y'),
        ], true);
    }

    /**
     * Build a ZIP of all the user's document PDFs. Returns the temp file path.
     */
    public function zipAll(User $user): string
    {
        $documents = Document::where('user_id', $user->id)->get();

        $tempPath = tempnam(sys_get_temp_dir(), 'docs_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($documents as $document) {
            $absolute = storage_path('app/public/' . $document->file_path);
            if (is_file($absolute)) {
                $zip->addFile($absolute, $document->reference . '.pdf');
            }
        }

        $zip->close();

        return $tempPath;
    }

    /**
     * Issue (or reuse) a revocable share token for a document.
     */
    public function shareLink(Document $document, bool $rotate = false): string
    {
        if ($rotate || ! $document->share_token) {
            $document->share_token = Str::random(40);
            $document->save();
        }

        return \URL::temporarySignedRoute(
            'documents.shared',
            now()->addDays(7),
            ['token' => $document->share_token]
        );
    }

    protected function findExisting(string $type, ?Model $related, int $userId): ?Document
    {
        $query = Document::where('user_id', $userId)->where('document_type', $type);

        if ($related) {
            $query->where('related_type', $related->getMorphClass())->where('related_id', $related->getKey());
        } else {
            $query->whereNull('related_id');
        }

        return $query->latest('id')->first();
    }

    protected function nextReference(string $type): string
    {
        $code = static::TYPE_CODES[$type] ?? 'DOC';
        $year = now()->format('Y');
        $seq = (Document::max('id') ?? 0) + 1;

        return 'DOC-' . $code . '-' . $year . '-' . str_pad((string) $seq, 5, '0', STR_PAD_LEFT);
    }
}
