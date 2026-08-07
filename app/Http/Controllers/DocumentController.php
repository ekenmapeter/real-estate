<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Services\DocumentService;
use App\Support\DocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    protected DocumentService $documents;

    public function __construct(DocumentService $documents)
    {
        $this->documents = $documents;
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $category = $request->input('category', 'all');
        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $dateRange = $request->input('date_range', 'all'); // today, 7d, month, custom
        $from = $request->input('from');
        $to = $request->input('to');
        $perPage = in_array((int) $request->input('per_page', 8), [8, 20, 50], true) ? (int) $request->input('per_page') : 8;

        [$from, $to] = $this->resolveDateRange($dateRange, $from, $to);

        $query = Document::forUser($user->id)
            ->ofCategory($category)
            ->ofStatus($status)
            ->search($search)
            ->betweenDates($from, $to);

        $documents = $query->latest('issued_at')->paginate($perPage)->withQueryString();

        $stats = [];
        foreach (DocumentTypes::CATEGORIES as $key => $label) {
            $stats[$key] = Document::forUser($user->id)->where('category', $key)->count();
        }
        $stats['all'] = array_sum($stats);

        $demo = $documents->total() === 0;
        if ($demo) {
            $stats = [
                'all' => 52,
                'project_investment' => 14,
                'property' => 8,
                'finance' => 16,
                'marketplace' => 9,
                'verification' => 5,
                'statement' => 8,
            ];
        }

        $rows = $this->buildRows($documents, $demo, $category, $status);

        $latestDocument = Document::forUser($user->id)->latest('id')->first();
        $shareUrl = $latestDocument ? route('documents.share', $latestDocument) : null;

        $categories = DocumentTypes::CATEGORIES;
        $statuses = ['new', 'active', 'completed', 'verified', 'pending', 'archived', 'rejected'];

        return view('documents.index', compact(
            'documents',
            'rows',
            'demo',
            'stats',
            'shareUrl',
            'categories',
            'statuses',
            'category',
            'status',
            'search',
            'dateRange',
            'from',
            'to',
            'perPage'
        ));
    }

    /**
     * Normalize documents (or demo rows) into a data-driven table row collection.
     */
    protected function buildRows($documents, bool $demo, string $category, string $status): array
    {
        if (! $demo) {
            $rows = [];
            foreach ($documents as $doc) {
                $rows[] = [
                    'title' => $doc->title,
                    'reference' => $doc->reference,
                    'category' => $doc->categoryLabel(),
                    'category_class' => $this->categoryClass($doc->category),
                    'icon' => $this->categoryIcon($doc->category),
                    'related' => $doc->relatedLabel(),
                    'date' => $doc->issued_at?->format('M d, Y'),
                    'status' => $doc->statusLabel(),
                    'status_class' => $this->statusClass($doc->status),
                    'view_url' => route('documents.view', $doc),
                    'download_url' => route('documents.download', $doc),
                ];
            }

            return $rows;
        }

        $rows = [
            ['title' => 'Investment Agreement', 'reference' => 'DOC-INV-2024-00125', 'category' => 'Project Investment', 'category_class' => $this->categoryClass('project_investment'), 'icon' => $this->categoryIcon('project_investment'), 'related' => 'Oceanview Residences (PRJ-001)', 'date' => 'May 20, 2026', 'status' => 'Active', 'status_class' => $this->statusClass('active')],
            ['title' => 'Share Certificate', 'reference' => 'DOC-SHR-2024-00125', 'category' => 'Project Investment', 'category_class' => $this->categoryClass('project_investment'), 'icon' => $this->categoryIcon('project_investment'), 'related' => 'Oceanview Residences (PRJ-001)', 'date' => 'May 20, 2026', 'status' => 'Active', 'status_class' => $this->statusClass('active')],
            ['title' => 'Deposit Receipt', 'reference' => 'DOC-DEP-2024-00098', 'category' => 'Finance', 'category_class' => $this->categoryClass('finance'), 'icon' => $this->categoryIcon('finance'), 'related' => 'DEP-2026-000098', 'date' => 'May 19, 2026', 'status' => 'Completed', 'status_class' => $this->statusClass('completed')],
            ['title' => 'Withdrawal Confirmation', 'reference' => 'DOC-WDR-2024-00076', 'category' => 'Finance', 'category_class' => $this->categoryClass('finance'), 'icon' => $this->categoryIcon('finance'), 'related' => 'WDR-2026-000076', 'date' => 'May 18, 2026', 'status' => 'Completed', 'status_class' => $this->statusClass('completed')],
            ['title' => 'AVC Purchase Receipt', 'reference' => 'DOC-MKT-2024-00045', 'category' => 'AVC Marketplace', 'category_class' => $this->categoryClass('marketplace'), 'icon' => $this->categoryIcon('marketplace'), 'related' => 'Trade #MKT-00045', 'date' => 'May 18, 2026', 'status' => 'Completed', 'status_class' => $this->statusClass('completed')],
            ['title' => 'Escrow Release Certificate', 'reference' => 'DOC-ESC-2024-00032', 'category' => 'AVC Marketplace', 'category_class' => $this->categoryClass('marketplace'), 'icon' => $this->categoryIcon('marketplace'), 'related' => 'Escrow #ESC-00032', 'date' => 'May 17, 2026', 'status' => 'Active', 'status_class' => $this->statusClass('active')],
            ['title' => 'Monthly Finance Statement', 'reference' => 'DOC-STMT-2026-05', 'category' => 'Statement', 'category_class' => $this->categoryClass('statement'), 'icon' => $this->categoryIcon('statement'), 'related' => 'May 2026', 'date' => 'May 15, 2026', 'status' => 'New', 'status_class' => $this->statusClass('new')],
            ['title' => 'KYC Verification', 'reference' => 'DOC-KYC-2024-00019', 'category' => 'Verification', 'category_class' => $this->categoryClass('verification'), 'icon' => $this->categoryIcon('verification'), 'related' => 'Personal', 'date' => 'May 10, 2026', 'status' => 'Verified', 'status_class' => $this->statusClass('verified')],
        ];

        if ($category !== 'all') {
            $label = DocumentTypes::CATEGORIES[$category] ?? null;
            $rows = array_values(array_filter($rows, fn ($r) => $r['category'] === $label));
        }
        if ($status !== 'all') {
            $rows = array_values(array_filter($rows, fn ($r) => strtolower($r['status']) === $status));
        }

        foreach ($rows as &$row) {
            $row['view_url'] = null;
            $row['download_url'] = null;
        }
        unset($row);

        return $rows;
    }

    protected function categoryClass(string $category): string
    {
        return match ($category) {
            'project_investment' => 'bg-blue-50 text-blue-700',
            'property' => 'bg-purple-50 text-purple-700',
            'finance' => 'bg-orange-50 text-orange-700',
            'marketplace' => 'bg-pink-50 text-pink-700',
            'verification' => 'bg-teal-50 text-teal-700',
            'statement' => 'bg-slate-100 text-slate-600',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    protected function categoryIcon(string $category): string
    {
        return match ($category) {
            'project_investment' => 'heroicon-o-rocket-launch',
            'property' => 'heroicon-o-building-office-2',
            'finance' => 'heroicon-o-wallet',
            'marketplace' => 'heroicon-o-arrows-right-left',
            'verification' => 'heroicon-o-shield-check',
            'statement' => 'heroicon-o-document-chart-bar',
            default => 'heroicon-o-document-text',
        };
    }

    protected function statusClass(string $status): string
    {
        return match ($status) {
            'active', 'completed' => 'bg-emerald-50 text-emerald-700',
            'new' => 'bg-blue-50 text-blue-700',
            'verified' => 'bg-teal-50 text-teal-700',
            'pending' => 'bg-amber-50 text-amber-700',
            'archived' => 'bg-slate-100 text-slate-600',
            'rejected' => 'bg-rose-50 text-rose-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    public function view(Document $document)
    {
        $this->ensureOwner($document);

        return view('documents.show', compact('document'));
    }

    public function download(Document $document): StreamedResponse
    {
        $this->ensureOwner($document);

        $absolute = storage_path('app/public/' . $document->file_path);
        if (! is_file($absolute)) {
            abort(404, 'Document file not found.');
        }

        return response()->streamDownload(
            fn () => readfile($absolute),
            $document->reference . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }

    public function print(Document $document)
    {
        $this->ensureOwner($document);

        $absolute = storage_path('app/public/' . $document->file_path);
        if (! is_file($absolute)) {
            abort(404, 'Document file not found.');
        }

        return response()->file($absolute, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->reference . '.pdf"',
        ]);
    }

    public function share(Request $request, Document $document)
    {
        $this->ensureOwner($document);

        $link = $this->documents->shareLink($document, $request->boolean('rotate'));

        if ($request->wantsJson()) {
            return response()->json(['link' => $link]);
        }

        return redirect()->route('documents.view', $document)->with('share_link', $link);
    }

    public function shared(Request $request, string $token)
    {
        if (! $request->hasValidSignature()) {
            abort(419, 'This share link has expired. Request a new one from your Documents page.');
        }

        $document = Document::where('share_token', $token)->firstOrFail();

        $absolute = storage_path('app/public/' . $document->file_path);
        if (! is_file($absolute)) {
            abort(404, 'Document file not found.');
        }

        return response()->file($absolute, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->reference . '.pdf"',
        ]);
    }

    public function zip()
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $path = $this->documents->zipAll($user);

        return response()->download($path, 'all-documents.zip', ['Content-Type' => 'application/zip'])->deleteFileAfterSend(true);
    }

    public function statement(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $period = $request->input('period') ?: now()->format('Y-m');
        $document = $this->documents->monthlyStatement($user, $period);

        if (! $document) {
            return redirect()->route('documents.index')->with('error', 'Could not generate the statement. Please try again.');
        }

        return redirect()->route('documents.view', $document)->with('success', 'Statement for ' . ($document->metadata['period_label'] ?? $period) . ' generated.');
    }

    protected function resolveDateRange(string $range, ?string $from, ?string $to): array
    {
        return match ($range) {
            'today' => [now()->startOfDay()->toDateString(), now()->endOfDay()->toDateString()],
            '7d' => [now()->subDays(6)->startOfDay()->toDateString(), now()->endOfDay()->toDateString()],
            'month' => [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()],
            'custom' => [$from, $to],
            default => [null, null],
        };
    }

    protected function ensureOwner(Document $document): void
    {
        if (! Auth::check() || (Auth::id() !== $document->user_id && ! Auth::user()->isAdmin())) {
            abort(403, 'You do not have access to this document.');
        }
    }
}
