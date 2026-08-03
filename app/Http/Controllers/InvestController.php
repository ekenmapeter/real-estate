<?php

namespace App\Http\Controllers;

use App\Mail\ProjectInvestmentConfirmationMail;
use App\Models\Project;
use App\Models\ProjectInvestment;
use App\Models\SavedProject;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvestController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('status', 'active');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }
        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', 'like', "%{$category}%");
            }
        }

        $projects = $query->orderBy('id', 'desc')->get();

        $savedProjectIds = Auth::check()
            ? SavedProject::where('user_id', Auth::id())->pluck('project_id')->all()
            : [];

        return view('invest', compact('projects', 'savedProjectIds'));
    }

    public function show(Project $project)
    {
        $raisedAmount = $project->raisedAmount();
        $fundedPercent = $project->fundedPercent();
        $isSaved = Auth::check() && SavedProject::where('user_id', Auth::id())->where('project_id', $project->id)->exists();

        return view('project-detail', compact('project', 'raisedAmount', 'fundedPercent', 'isSaved'));
    }

    public function invest(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($project->status !== 'active') {
            return redirect()->back()->with('error', 'This project is no longer open for investment.');
        }

        $amount = round((float) $request->amount, 2);

        if ($amount < $project->minimum_investment) {
            return redirect()->back()->with('error', 'Minimum investment for this project is ' . format_avc($project->minimum_investment) . '.');
        }

        $remaining = max(0, (float) $project->target_amount - $project->raisedAmount());
        if ($amount > $remaining) {
            return redirect()->back()->with('error', 'Only ' . format_avc($remaining) . ' remains to be raised for this project.');
        }

        if ($user->wallet_balance < $amount) {
            return redirect()->back()->with('error', 'Insufficient AVC balance. You need ' . format_avc($amount) . ' to invest in this project.');
        }

        $user->wallet_balance -= $amount;
        $user->save();

        $expectedRoi = ($amount * $project->expected_return_percentage) / 100;

        $investment = ProjectInvestment::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'amount' => $amount,
            'expected_roi_amount' => $expectedRoi,
            'roi_earned' => 0.00,
            'status' => 'active',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'project_investment',
            'amount' => $amount,
            'reference' => 'PINV-' . $investment->id,
            'description' => 'Invested ' . format_avc($amount) . ' in ' . $project->title,
            'status' => 'completed',
        ]);

        Mail::to($user->email)->send(new ProjectInvestmentConfirmationMail($investment));

        return redirect()->route('project.show', $project)
            ->with('success', 'Successfully invested ' . format_avc($amount) . ' in ' . $project->title . '!');
    }

    public function downloadDocument(Project $project)
    {
        if (!$project->document_path || !file_exists(storage_path('app/public/' . $project->document_path))) {
            return redirect()->back()->with('error', 'No document is available for this project yet.');
        }

        $filename = str_replace(' ', '-', strtolower($project->title)) . '-document.pdf';

        return response()->download(storage_path('app/public/' . $project->document_path), $filename);
    }

    public function toggleSave(Project $project)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $existing = SavedProject::where('user_id', $user->id)->where('project_id', $project->id)->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            SavedProject::create([
                'user_id' => $user->id,
                'project_id' => $project->id,
            ]);
            $saved = true;
        }

        if (request()->wantsJson()) {
            return response()->json(['saved' => $saved]);
        }

        return redirect()->back()->with('success', $saved
            ? 'Project "' . $project->title . '" saved to your list!'
            : 'Project removed from your saved list.');
    }
}
