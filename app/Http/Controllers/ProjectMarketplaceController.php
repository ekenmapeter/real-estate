<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\SavedProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMarketplaceController extends Controller
{
    /**
     * Display the Project Marketplace catalog.
     */
    public function index(Request $request)
    {
        $query = Project::where('status', 'active');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('property_type', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', 'like', "%{$category}%");
            }
        }

        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'funding_high':
                $query->orderBy('target_amount', 'desc');
                break;
            case 'return_high':
                $query->orderBy('expected_return_percentage', 'desc');
                break;
            case 'closing_soon':
                $query->orderBy('funding_closing_date', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $projects = $query->withCount('reviews')->get();

        $savedProjectIds = Auth::check()
            ? SavedProject::where('user_id', Auth::id())->pluck('project_id')->all()
            : [];

        return view('marketplace.index', compact('projects', 'savedProjectIds'));
    }

    /**
     * Display detailed View Project page replacing the old More Info page.
     */
    public function show(Project $project)
    {
        $project->load(['reviews.user', 'images', 'documents', 'updates', 'durationTiers']);
        
        $tiers = $project->getAvailableTiers();
        $raisedAmount = $project->raisedAmount();
        $fundedPercent = $project->fundedPercent();
        $isSaved = Auth::check() && SavedProject::where('user_id', Auth::id())->where('project_id', $project->id)->exists();

        $userCycles = Auth::check()
            ? $project->shareCycles()->where('user_id', Auth::id())->get()
            : collect();

        $user = Auth::user();

        return view('marketplace.show', compact('project', 'tiers', 'raisedAmount', 'fundedPercent', 'isSaved', 'userCycles', 'user'));
    }

    /**
     * Download project document securely (enforcing KYC check for restricted documents).
     */
    public function downloadDocument(Project $project, $documentId)
    {
        $doc = ProjectDocument::where('project_id', $project->id)->where('id', $documentId)->firstOrFail();

        $user = Auth::user();
        if ($doc->is_restricted) {
            if (!$user || $user->kyc_status !== 'approved') {
                return redirect()->back()->with('error', 'Only verified users (KYC Approved) can download restricted project documents.');
            }
        }

        if (!file_exists(storage_path('app/public/' . $doc->file_path))) {
            return redirect()->back()->with('error', 'Document file is currently unavailable.');
        }

        return response()->download(storage_path('app/public/' . $doc->file_path), $doc->title . '.pdf');
    }
}
