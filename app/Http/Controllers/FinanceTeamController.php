<?php

namespace App\Http\Controllers;

use App\Models\FinanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FinanceTeamController extends Controller
{
    /**
     * Dedicated Finance Team Requests Hub (/finance/team)
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $tab = $request->query('tab', 'all');

        $query = FinanceRequest::where('user_id', $user->id);

        if ($tab === 'open') {
            $query->whereIn('status', ['under_review', 'payment_instructions_assigned', 'evidence_submitted', 'under_verification']);
        } elseif ($tab === 'pending') {
            $query->whereIn('status', ['under_review', 'under_verification']);
        } elseif ($tab === 'action_required') {
            $query->where('status', 'payment_instructions_assigned');
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        } elseif ($tab === 'cancelled') {
            $query->whereIn('status', ['rejected', 'cancelled']);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Counts for tab badges
        $counts = [
            'all' => FinanceRequest::where('user_id', $user->id)->count(),
            'open' => FinanceRequest::where('user_id', $user->id)->whereIn('status', ['under_review', 'payment_instructions_assigned', 'evidence_submitted', 'under_verification'])->count(),
            'pending' => FinanceRequest::where('user_id', $user->id)->whereIn('status', ['under_review', 'under_verification'])->count(),
            'action_required' => FinanceRequest::where('user_id', $user->id)->where('status', 'payment_instructions_assigned')->count(),
            'completed' => FinanceRequest::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => FinanceRequest::where('user_id', $user->id)->whereIn('status', ['rejected', 'cancelled'])->count(),
        ];

        return view('finance.team.index', compact('requests', 'tab', 'counts'));
    }

    /**
     * Show Create Request Form (/finance/team/create)
     */
    public function create(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $type = $request->query('type', 'deposit');
        if (!in_array($type, ['deposit', 'withdrawal'])) {
            $type = 'deposit';
        }

        return view('finance.team.create', compact('user', 'type'));
    }

    /**
     * Store new Finance Request (/finance/team/store)
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'type' => 'required|in:deposit,withdrawal',
            'country' => 'required|string|max:100',
            'currency' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:100',
            'sender_name' => 'required|string|max:150',
            'sender_account' => 'required|string|max:150',
            'sender_email' => 'required|email|max:150',
            'user_notes' => 'nullable|string|max:1000',
        ]);

        // Generate unique request ID e.g. FR-250520-0001
        $datePrefix = date('ymd');
        $randomSequence = strtoupper(Str::random(4));
        $requestId = 'FR-' . $datePrefix . '-' . $randomSequence;

        $financeRequest = FinanceRequest::create([
            'request_id' => $requestId,
            'user_id' => $user->id,
            'type' => $validated['type'],
            'country' => $validated['country'],
            'currency' => $validated['currency'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'sender_name' => $validated['sender_name'],
            'sender_account' => $validated['sender_account'],
            'sender_email' => $validated['sender_email'],
            'user_notes' => $validated['user_notes'] ?? null,
            'status' => 'under_review',
        ]);

        return redirect()->route('finance.team.show', $financeRequest->request_id)
            ->with('success', 'Your finance request has been submitted successfully! Our team will review and provide payment details shortly.');
    }

    /**
     * Show Request Timeline & Details (/finance/team/request/{request_id})
     */
    public function show($request_id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $financeRequest = FinanceRequest::where('request_id', $request_id)->firstOrFail();

        if ($financeRequest->user_id !== $user->id) {
            abort(403, 'Unauthorized access to finance request.');
        }

        return view('finance.team.show', compact('financeRequest'));
    }

    /**
     * Upload Payment Evidence (/finance/team/request/{request_id}/evidence)
     */
    public function uploadEvidence(Request $request, $request_id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $financeRequest = FinanceRequest::where('request_id', $request_id)->firstOrFail();

        if ($financeRequest->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240', // 10MB
            'evidence_notes' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('finance_evidence', 'public');
            $financeRequest->payment_evidence = $path;
        }

        $financeRequest->evidence_notes = $request->input('evidence_notes');
        $financeRequest->evidence_submitted_at = Carbon::now();
        $financeRequest->status = 'evidence_submitted';
        $financeRequest->save();

        return redirect()->back()->with('success', 'Payment evidence submitted! Our finance team is reviewing your payment.');
    }

    /**
     * Cancel Finance Request (/finance/team/request/{request_id}/cancel)
     */
    public function cancel(Request $request, $request_id)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $financeRequest = FinanceRequest::where('request_id', $request_id)->firstOrFail();

        if ($financeRequest->user_id !== $user->id) {
            abort(403);
        }

        if (in_array($financeRequest->status, ['completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'This request cannot be cancelled.');
        }

        $financeRequest->status = 'cancelled';
        $financeRequest->save();

        return redirect()->route('finance.team.index')->with('success', 'Request has been cancelled.');
    }
}
