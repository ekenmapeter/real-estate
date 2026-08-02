@extends('layouts.main')

@section('title', 'Edit Project | Radiant Dream Realty')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    body { background-color: #f8fafc !important; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.dashboard', ['tab' => 'projects']) }}" class="text-muted small fw-semibold text-decoration-none mb-1 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Back to Admin Panel
            </a>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2" style="color:#7c3aed;"></i>Edit Project</h3>
            <small class="text-muted">Update project details — changes go live immediately.</small>
        </div>
        <form action="{{ route('admin.project.delete', $project->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger fw-bold rounded-3" onclick="return confirm('Delete project &quot;{{ $project->title }}&quot;? This permanently removes the project, all investments and saved records.')">
                <i class="bi bi-trash me-1"></i> Delete Project
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
            <div><strong class="d-block">Success!</strong><span>{{ session('success') }}</span></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <strong class="d-block mb-1">Please fix the following:</strong>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill me-2" style="color:#7c3aed;"></i>Project Details</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark small">Project Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $project->title }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark small">Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $project->location }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark small">Category</label>
                    <select name="category" class="form-select">
                        @foreach(['Residential', 'Commercial', 'Luxury', 'Vacation', 'Land', 'Multi-Family'] as $cat)
                            <option value="{{ $cat }}" @selected($project->category === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark small">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" @selected($project->status === 'active')>Active</option>
                        <option value="completed" @selected($project->status === 'completed')>Completed</option>
                        <option value="closed" @selected($project->status === 'closed')>Closed</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark small">Image URL</label>
                    <input type="url" name="image_url" class="form-control" value="{{ $project->image_url }}" placeholder="https://...">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold text-dark small">Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Describe the project, location benefits, and return expectations...">{{ $project->description }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up-arrow me-2" style="color:#7c3aed;"></i>Funding Settings</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark small">Target Amount ($)</label>
                    <input type="number" step="0.01" min="1" name="target_amount" class="form-control" value="{{ $project->target_amount }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark small">Minimum Investment ($)</label>
                    <input type="number" step="0.01" min="1" name="minimum_investment" class="form-control" value="{{ $project->minimum_investment }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark small">Expected Return (%)</label>
                    <input type="number" step="0.1" min="0" max="1000" name="expected_return_percentage" class="form-control" value="{{ $project->expected_return_percentage }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark small">Rating (0 - 5)</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="{{ $project->rating }}" placeholder="4.5">
                    <small class="text-muted">Shown as stars on project listings.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-dark small">Duration (Months)</label>
                    <input type="number" min="1" name="investment_duration_months" class="form-control" value="{{ $project->investment_duration_months }}" required>
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-images me-2" style="color:#7c3aed;"></i>Photo Gallery</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark small">Add Gallery Images (Upload)</label>
                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Upload multiple images at once. They appear in a carousel on the project page.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark small">Gallery Image URLs (one per line)</label>
                    <textarea name="gallery_urls" class="form-control" rows="3" placeholder="https://...&#10;https://...">{{ $project->images->filter(fn($img) => str_starts_with($img->image_path, 'http'))->pluck('image_path')->implode(PHP_EOL) }}</textarea>
                    <small class="text-muted">Saving replaces the entire gallery. Existing URL images are pre-filled above.</small>
                </div>
            </div>

            @if($project->images->count() > 0)
                <div class="mt-3">
                    <label class="form-label fw-semibold text-dark small">Current Gallery ({{ $project->images->count() }})</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->images as $image)
                            <div class="position-relative" style="width:120px;">
                                <img src="{{ $image->url() }}" alt="Gallery image" style="width:120px; height:80px; object-fit:cover; border-radius:10px; border:1px solid #e2e8f0;">
                                <form action="{{ route('admin.gallery.delete', $image->id) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:22px; height:22px;" title="Remove image" onclick="return confirm('Remove this gallery image?')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-pdf me-2" style="color:#7c3aed;"></i>Project Document</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark small">Replace Document (PDF/DOC)</label>
                    <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                    <small class="text-muted">Uploading a new document replaces the current one. Leave empty to keep it.</small>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    @if($project->document_path)
                        <a href="{{ Storage::disk('public')->url($project->document_path) }}" target="_blank" class="btn btn-outline-primary fw-bold rounded-3">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i> View Current Document
                        </a>
                    @else
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>No document uploaded yet.</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
            <a href="{{ route('admin.dashboard', ['tab' => 'projects']) }}" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3">Cancel</a>
        </div>
    </form>
</div>
@endsection
