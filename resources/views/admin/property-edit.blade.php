@extends('layouts.main')

@section('title', 'Edit Property | Radiant Dream Realty')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    body { background-color: #f8fafc !important; }
    .preview-card-img { height: 200px; object-fit: cover; width: 100%; }
</style>

<div class="container py-4" x-data="propertyEditEngine()" x-init="setValuesFromServer()">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-muted small fw-semibold text-decoration-none mb-1 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Back to Admin Panel
            </a>
            <h3 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square me-2" style="color:#2563eb;"></i>Edit Property</h3>
            <small class="text-muted">Update listing details — changes go live immediately.</small>
        </div>
        <form action="{{ route('admin.property.delete', $property->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger fw-bold rounded-3" onclick="return confirm('Delete "{{ $property->title }}"? This permanently removes the listing and all linked investments.')">
                <i class="bi bi-trash me-1"></i> Delete Property
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

    <div class="row g-4">
        <!-- Edit Form -->
        <div class="col-lg-7">
            <form action="{{ route('admin.property.update', $property->id) }}" method="POST">
                @csrf
                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill me-2" style="color:#2563eb;"></i>Listing Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Property Title</label>
                            <input type="text" name="title" class="form-control" x-model="form.title" value="{{ $property->title }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Location</label>
                            <input type="text" name="location" class="form-control" x-model="form.location" value="{{ $property->location }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Category</label>
                            <select name="category" class="form-select" x-model="form.category" required>
                                @foreach(['Residential', 'Commercial', 'Luxury', 'Vacation', 'Land', 'Multi-Family'] as $cat)
                                    <option value="{{ $cat }}" @selected($property->category === $cat)>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Status</label>
                            <select name="status" class="form-select" x-model="form.status">
                                <option value="active" @selected($property->status === 'active')>Active</option>
                                <option value="sold_out" @selected($property->status === 'sold_out')>Sold Out</option>
                                <option value="upcoming" @selected($property->status === 'upcoming')>Upcoming</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark small">Image URL</label>
                            <input type="url" name="image_url" class="form-control" x-model="form.image_url" value="{{ $property->image_url }}" placeholder="https://...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Description</label>
                            <textarea name="description" class="form-control" rows="4" x-model="form.description" placeholder="Describe the property, amenities, and investment opportunity...">{{ $property->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 shadow-sm bg-white p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up-arrow me-2" style="color:#7c3aed;"></i>Investment Settings</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Price Per Share ($)</label>
                            <input type="number" step="0.01" min="1" name="price_per_share" class="form-control" x-model="form.price_per_share" value="{{ $property->price_per_share }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Target ROI (%)</label>
                            <input type="number" step="0.1" min="0" max="1000" name="roi_percentage" class="form-control" x-model="form.roi_percentage" value="{{ $property->roi_percentage }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Total Shares</label>
                            <input type="number" min="1" name="total_shares" class="form-control" x-model="form.total_shares" value="{{ $property->total_shares }}" required>
                            <small class="text-muted">{{ $property->available_shares }} of {{ $property->total_shares }} shares currently available. Total cannot drop below shares already sold.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Investment Duration (Months)</label>
                            <input type="number" min="1" name="investment_duration_months" class="form-control" x-model="form.duration" value="{{ $property->investment_duration_months }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                        <i class="bi bi-check-lg me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background:#7c3aed;" @click="openPreview()">
                        <i class="bi bi-eye me-1"></i> Preview Listing
                    </button>
                    <a href="{{ route('admin.dashboard', ['tab' => 'properties']) }}" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Live Preview -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden sticky-lg-top" style="top: 90px;">
                <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="background:#0b1329;">
                    <h6 class="fw-bold text-white mb-0"><i class="bi bi-eye me-2"></i>Live Preview</h6>
                    <span class="badge fw-semibold rounded-pill" style="background:rgba(34,197,94,0.2); color:#4ade80; font-size:0.68rem;">Updating in real time</span>
                </div>
                <div class="p-4">
                    <div class="card border-0 rounded-4 overflow-hidden" style="box-shadow:0 10px 30px -12px rgba(15,23,42,0.2);">
                        <div class="position-relative">
                            <img :src="preview.image_url || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1000&auto=format&fit=crop'" class="preview-card-img" alt="Property preview">
                            <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                                <span class="badge bg-warning text-dark fw-bold" x-text="preview.category || 'Residential'">Residential</span>
                                <span class="badge bg-success" x-text="preview.roi_percentage + '% ROI'">12% ROI</span>
                            </div>
                            <span class="badge position-absolute top-0 end-0 m-3 fw-bold rounded-pill" :class="preview.status === 'active' ? 'bg-success' : preview.status === 'upcoming' ? 'bg-info' : 'bg-secondary'" x-text="preview.status || 'active'">active</span>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-1" style="color:#0f172a;" x-text="preview.title || 'Untitled Property'">Untitled Property</h5>
                            <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i><span x-text="preview.location || 'Location TBA'">Location TBA</span></p>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Funded: <span x-text="preview.fundedPercent + '%'">0%</span></span>
                                    <span class="text-primary" x-text="'$' + preview.raisedAmount.toFixed(2) + ' / $' + preview.totalValuation.toFixed(2)">$0.00 / $0.00</span>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-warning" :style="'width:' + preview.fundedPercent + '%'"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between border-top pt-3 text-muted small mb-3">
                                <span>Share Price: <b class="text-dark" x-text="'$' + Number(preview.price_per_share).toFixed(2)">$0.00</b></span>
                                <span>Est. Yield: <b class="text-success" x-text="preview.roi_percentage + '% p.a.'">0% p.a.</b></span>
                            </div>
                            <div class="small text-muted mb-3" x-show="preview.description">
                                <i class="bi bi-chat-left-text me-1"></i>
                                <span x-text="preview.description">—</span>
                            </div>
                            <button class="btn btn-warning text-dark fw-bold w-100 py-2 rounded-3 shadow-sm" type="button">
                                <i class="bi bi-cart-plus me-1"></i> Buy Shares
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Full Preview Modal -->
<div x-show="showPreviewModal" x-cloak style="position:fixed;inset:0;z-index:99999;background:rgba(11,19,41,0.75);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;padding:1rem;">
    <div class="bg-white rounded-4 shadow-lg p-4" style="max-width:640px;width:100%;max-height:90vh;overflow-y:auto;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-eye me-2" style="color:#7c3aed;"></i>Listing Preview</h5>
            <button type="button" class="btn-close" @click="showPreviewModal = false"></button>
        </div>
        <div class="card border-0 rounded-4 overflow-hidden">
            <div class="position-relative">
                <img :src="preview.image_url || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1000&auto=format&fit=crop'" style="height:280px;width:100%;object-fit:cover;" alt="Property preview">
                <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                    <span class="badge bg-warning text-dark fw-bold" x-text="preview.category || 'Residential'"></span>
                    <span class="badge bg-success" x-text="preview.roi_percentage + '% ROI'"></span>
                </div>
            </div>
            <div class="p-4">
                <h4 class="fw-bold mb-1" style="color:#0f172a;" x-text="preview.title || 'Untitled Property'"></h4>
                <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i><span x-text="preview.location || 'Location TBA'"></span></p>
                <p class="text-muted small" x-show="preview.description" x-text="preview.description"></p>
                <div class="row g-2 mt-2">
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">SHARE PRICE</small><strong class="text-dark" x-text="'$' + Number(preview.price_per_share).toFixed(2)"></strong></div></div>
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">TOTAL VALUATION</small><strong class="text-dark" x-text="'$' + preview.totalValuation.toFixed(2)"></strong></div></div>
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">ROI</small><strong class="text-success" x-text="preview.roi_percentage + '%'"></strong></div></div>
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">DURATION</small><strong class="text-dark" x-text="preview.duration + ' months'"></strong></div></div>
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">SHARES</small><strong class="text-dark" x-text="preview.available_shares + ' / ' + preview.total_shares"></strong></div></div>
                    <div class="col-6"><div class="p-3 rounded-3 border bg-light"><small class="text-muted d-block">FUNDED</small><strong class="text-warning" x-text="preview.fundedPercent + '%'"></strong></div></div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-outline-secondary fw-bold px-4" @click="showPreviewModal = false">Close</button>
        </div>
    </div>
</div>

<script>
    function propertyEditEngine() {
        return {
            form: {
                title: '',
                location: '',
                category: '',
                status: '',
                image_url: '',
                description: '',
                price_per_share: 0,
                roi_percentage: 0,
                total_shares: 0,
                duration: 0,
            },
            preview: {},
            showPreviewModal: false,

            setValuesFromServer() {
                this.form = {
                    title: {{ json_encode($property->title) }},
                    location: {{ json_encode($property->location) }},
                    category: {{ json_encode($property->category) }},
                    status: {{ json_encode($property->status) }},
                    image_url: {{ json_encode($property->image_url) }},
                    description: {{ json_encode($property->description) }},
                    price_per_share: parseFloat({{ $property->price_per_share }}),
                    roi_percentage: parseFloat({{ $property->roi_percentage }}),
                    total_shares: parseInt({{ $property->total_shares }}),
                    duration: parseInt({{ $property->investment_duration_months }}),
                };
                this.syncPreview();
            },

            syncPreview() {
                const total = parseFloat(this.form.total_shares) || 0;
                const available = Math.min(parseFloat(this.form.total_shares) || 0, {{ $property->available_shares }});
                const sold = Math.max(total - available, 0);
                const price = parseFloat(this.form.price_per_share) || 0;
                this.preview = {
                    title: this.form.title,
                    location: this.form.location,
                    category: this.form.category,
                    status: this.form.status,
                    image_url: this.form.image_url,
                    description: this.form.description,
                    price_per_share: price,
                    roi_percentage: this.form.roi_percentage || 0,
                    total_shares: total,
                    available_shares: available,
                    duration: this.form.duration || 0,
                    fundedPercent: total > 0 ? Math.round((sold / total) * 100) : 0,
                    raisedAmount: sold * price,
                    totalValuation: total * price,
                };
            },

            openPreview() {
                this.syncPreview();
                this.showPreviewModal = true;
            }
        }
    }
</script>
@endsection
