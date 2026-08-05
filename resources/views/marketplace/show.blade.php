@extends('layouts.main')

@section('title', $project->title . ' | Project Marketplace')

@section('content')
<div class="container-fluid px-0" style="background-color: #f8fafc; min-height: 100vh;" x-data="projectCalculatorApp()">
    <div class="row g-0">
        
        <!-- Left Sidebar Column (Matching Mockup Image 2) -->
        <div class="col-12 col-md-4 col-lg-3 d-none d-md-block">
            @include('partials.app-sidebar')
        </div>

        <!-- Right Main Content Area -->
        <div class="col-12 col-md-8 col-lg-9 p-3 p-md-4">
            
            <!-- Breadcrumb & Top Actions -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('marketplace.index') }}" class="text-decoration-none text-muted fw-semibold small">
                    <i class="bi bi-arrow-left me-1"></i> Back to Project Marketplace
                </a>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold" onclick="toggleSaveProject({{ $project->id }}, this)">
                        <i class="bi {{ $isSaved ? 'bi-bookmark-fill text-primary' : 'bi-bookmark' }} me-1"></i> {{ $isSaved ? 'Saved' : 'Save' }}
                    </button>
                    <button class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold" onclick="shareProject('{{ $project->title }}', '{{ url()->current() }}')">
                        <i class="bi bi-share me-1"></i> Share
                    </button>
                </div>
            </div>

            <!-- Alert Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- LEFT COLUMN: Main Project Media, Overview & Specifications -->
                <div class="col-12 col-lg-7 col-xl-7">
                    
                    <!-- Hero Image & Badges -->
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-4 bg-white">
                        <div class="position-relative" style="height: 360px; background-color: #0f172a;">
                            <img src="{{ $project->image_url ?: asset('images/property-placeholder.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $project->title }}">
                            
                            <!-- Top Badge overlay -->
                            <div class="position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="bi bi-graph-up me-1"></i> {{ number_format($project->expected_return_percentage, 2) }}% Target Earnings
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Project Badges Strip -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-2 fw-bold"><i class="bi bi-play-circle-fill me-1"></i> {{ $project->statusLabel() }}</span>
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-2 fw-bold"><i class="bi bi-building me-1"></i> {{ $project->property_type ?: $project->category }}</span>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-2 fw-bold"><i class="bi bi-graph-up me-1"></i> {{ number_format($project->expected_return_percentage, 2) }}% Target Earnings</span>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-2 fw-bold"><i class="bi bi-calendar-event me-1"></i> {{ $project->investment_duration_months }} Months Funding Window</span>
                            </div>

                            <!-- Countdown Bar -->
                            <div class="p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center" style="background-color: #fffbeeb0; border: 1px solid #fef3c7;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-hourglass-split text-warning fs-4"></i>
                                    <div>
                                        <span class="text-uppercase small fw-bold text-muted d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">Funding Window Closes In</span>
                                        <span class="fw-bold text-dark fs-5">{{ $project->remainingDaysFormatted() }}</span>
                                    </div>
                                </div>
                                <span class="text-muted small fw-semibold">{{ $project->investment_duration_months }} months duration</span>
                            </div>

                            <!-- Funding Progress -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark"><i class="bi bi-bar-chart-fill text-primary me-1"></i> Funding Progress</span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $fundedPercent }}% Funded</span>
                                </div>
                                <div class="progress rounded-pill mb-2" style="height: 12px;">
                                    <div class="progress-bar rounded-pill bg-primary" role="progressbar" style="width: {{ min(100, $fundedPercent) }}%;"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span class="fw-bold text-primary">{{ number_format($raisedAmount, 0) }} AVC raised</span>
                                    <span>{{ number_format($project->target_amount, 0) }} AVC target</span>
                                </div>
                            </div>

                            <!-- Project Title & Location -->
                            <h3 class="fw-bold text-dark mb-1">{{ $project->title }}</h3>
                            <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $project->location }} &bull; <span class="text-success fw-semibold"><i class="bi bi-patch-check-fill me-1"></i> Verified Project</span></p>

                            <div class="d-flex align-items-center gap-2 mb-4">
                                <div class="text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="fw-bold text-dark ms-1">{{ $project->averageRating() }}</span>
                                </div>
                                <span class="text-muted small">({{ $project->reviewCount() }} reviews)</span>
                            </div>

                            <!-- About This Project -->
                            <div class="border-top pt-4 mb-4">
                                <h5 class="fw-bold text-dark mb-2">About This Project</h5>
                                <p class="text-secondary leading-relaxed mb-0">{{ $project->description ?: 'Gated real estate community project featuring high quality residential developments with strong rental demand.' }}</p>
                            </div>

                            <!-- Property Highlights Grid -->
                            <div class="border-top pt-4 mb-4">
                                <h5 class="fw-bold text-dark mb-3">Property Highlights</h5>
                                <div class="row g-3">
                                    <div class="col-6 col-sm-3">
                                        <div class="bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-house-door fs-3 text-primary d-block mb-1"></i>
                                            <span class="fw-bold text-dark d-block">{{ $project->total_units ?: '20 Units' }}</span>
                                            <span class="text-muted small">Total Homes</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-door-closed fs-3 text-primary d-block mb-1"></i>
                                            <span class="fw-bold text-dark d-block">{{ $project->bedrooms ?: '3-4 Beds' }}</span>
                                            <span class="text-muted small">Total Bedrooms</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-badge-wc fs-3 text-primary d-block mb-1"></i>
                                            <span class="fw-bold text-dark d-block">{{ $project->bathrooms ?: '2-3 Baths' }}</span>
                                            <span class="text-muted small">Total Bathrooms</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-aspect-ratio fs-3 text-primary d-block mb-1"></i>
                                            <span class="fw-bold text-dark d-block">{{ $project->land_size_sqm ?: '2,800 m²' }}</span>
                                            <span class="text-muted small">Land Size</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Details Table -->
                            <div class="border-top pt-4">
                                <h5 class="fw-bold text-dark mb-3">Project Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <tbody>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-info-circle me-2"></i> Project Status</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ $project->statusLabel() }}</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-calendar3 me-2"></i> Funding Window</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ $project->investment_duration_months }} months (Until {{ $project->endsAt() ? $project->endsAt()->format('M d, Y') : 'N/A' }})</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-star me-2"></i> Project Rating</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ $project->averageRating() }} / 5 ({{ $project->reviewCount() }} reviews)</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-people me-2"></i> Shareholders</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ $project->uniqueShareholdersCount() }} Investors</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-bullseye me-2"></i> Target Amount</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ number_format($project->target_amount, 0) }} AVC</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-tag me-2"></i> Share Price</td>
                                                <td class="fw-bold text-end py-2 text-primary fs-6">{{ number_format($project->share_price ?: 100, 2) }} AVC</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-layers me-2"></i> Shares Required To Activate</td>
                                                <td class="fw-bold text-end py-2 text-dark">
                                                    14 Days: 10 Shares &bull; 1 Month: 25 Shares &bull; 3 Months: 50 Shares
                                                </td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2"><i class="bi bi-graph-up-arrow me-2"></i> Expected Earnings</td>
                                                <td class="fw-bold text-end py-2 text-success">{{ number_format($project->expected_return_percentage, 2) }}%</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted py-2"><i class="bi bi-folder me-2"></i> Category</td>
                                                <td class="fw-bold text-end py-2 text-dark">{{ $project->property_type ?: $project->category }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Duration Selector, Activation Flow, Calculator & Buy Panel -->
                <div class="col-12 col-lg-5 col-xl-5">
                    
                    <!-- Available Earning Durations Selector -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-clock-history text-primary me-2"></i> Available Earning Durations</h5>
                            <p class="text-muted small mb-3">Choose your preferred duration. Each duration has a different minimum shares requirement to activate.</p>

                            <div class="d-flex flex-column gap-3">
                                @foreach($tiers as $tier)
                                    <div class="border rounded-3 p-3 position-relative cursor-pointer transition-all duration-card"
                                         :class="selectedDuration === '{{ $tier->duration_key }}' ? 'border-primary bg-primary bg-opacity-10' : 'bg-light border-light-subtle'"
                                         @click="selectDuration('{{ $tier->duration_key }}', {{ $tier->required_shares }}, {{ $tier->target_earnings_pct }})">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="radio" name="duration_tier_radio" class="form-check-input" :checked="selectedDuration === '{{ $tier->duration_key }}'">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $tier->duration_label }} Cycle</h6>
                                            </div>
                                            @if(isset($tier->is_popular) && $tier->is_popular)
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 small" style="font-size: 0.7rem;">Most Popular</span>
                                            @endif
                                        </div>

                                        <div class="row text-center g-2 border-top pt-2">
                                            <div class="col-4">
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">Required Shares</span>
                                                <span class="fw-bold text-dark">{{ $tier->required_shares }} shares</span>
                                            </div>
                                            <div class="col-4">
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">Min. Share Value</span>
                                                <span class="fw-bold text-dark">{{ number_format($tier->min_avc_value, 0) }} AVC</span>
                                            </div>
                                            <div class="col-4">
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">Target Earnings</span>
                                                <span class="fw-bold text-success">{{ number_format($tier->target_earnings_pct, 2) }}%</span>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2 text-center" style="font-size: 0.75rem;">Estimated completion in {{ $tier->duration_days }} days after activation.</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Share Activation Explanation Diagram -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-2"></i> Share Activation Explained</h6>
                            <div class="d-flex justify-content-between align-items-center text-center">
                                <div>
                                    <div class="rounded-circle bg-light text-primary mb-1 d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;"><i class="bi bi-cart"></i></div>
                                    <span class="small d-block fw-semibold" style="font-size: 0.72rem;">Buy Shares</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                                <div>
                                    <div class="rounded-circle bg-light text-primary mb-1 d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;"><i class="bi bi-check2-all"></i></div>
                                    <span class="small d-block fw-semibold" style="font-size: 0.72rem;">Meet Required</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                                <div>
                                    <div class="rounded-circle bg-light text-primary mb-1 d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;"><i class="bi bi-lightning-charge"></i></div>
                                    <span class="small d-block fw-semibold" style="font-size: 0.72rem;">Activate</span>
                                </div>
                                <i class="bi bi-chevron-right text-muted small"></i>
                                <div>
                                    <div class="rounded-circle bg-light text-primary mb-1 d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;"><i class="bi bi-graph-up-arrow"></i></div>
                                    <span class="small d-block fw-semibold" style="font-size: 0.72rem;">Earn & Receive</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Share Earnings Calculator & Buy Shares Panel -->
                    <div id="buy-panel" class="card border-0 rounded-4 shadow-lg mb-4 bg-white overflow-hidden">
                        <div class="card-header bg-primary text-white p-3 p-md-4 border-0">
                            <h5 class="fw-bold mb-0 text-white"><i class="bi bi-calculator me-2"></i> Share Earnings Calculator</h5>
                            <p class="text-white-50 small mb-0">Select duration and number of shares to estimate returns.</p>
                        </div>
                        
                        <div class="card-body p-4">
                            <form action="{{ route('share.buy', $project) }}" method="POST">
                                @csrf
                                <input type="hidden" name="duration_key" :value="selectedDuration">

                                <!-- Shares Quantity Input -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Number of Shares</label>
                                    <div class="input-group input-group-lg">
                                        <button type="button" class="btn btn-outline-secondary" @click="decrementShares()">-</button>
                                        <input type="number" name="shares" class="form-control text-center fw-bold text-primary" x-model.number="sharesCount" @input="updateCalculations()" min="1" required>
                                        <button type="button" class="btn btn-outline-secondary" @click="incrementShares()">+</button>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted small mt-1">
                                        <span>Required: <strong class="text-dark" x-text="requiredShares">10</strong> shares</span>
                                        <span>Share Price: <strong class="text-dark">{{ number_format($project->share_price ?: 100, 2) }} AVC</strong></span>
                                    </div>
                                </div>

                                <!-- Calculation Results Grid -->
                                <div class="bg-light p-3 rounded-3 mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Purchase Amount:</span>
                                        <span class="fw-bold text-dark" x-text="purchaseAmount.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' AVC'">0.00 AVC</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Target Earnings (<span x-text="targetPct + '%'"></span>):</span>
                                        <span class="fw-bold text-success" x-text="'+' + projectedEarnings.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' AVC'">+0.00 AVC</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Completion Value:</span>
                                        <span class="fw-bold text-primary" x-text="completionValue.toLocaleString('en-US', {minimumFractionDigits: 2}) + ' AVC'">0.00 AVC</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Activation Status:</span>
                                        <span class="badge px-3 py-1 rounded-pill" :class="sharesCount >= requiredShares ? 'bg-success' : 'bg-warning text-dark'" x-text="sharesCount >= requiredShares ? 'Active Earning Cycle' : 'Pending Activation'">Pending Activation</span>
                                    </div>
                                </div>

                                <!-- Security PIN Input -->
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Security PIN / Confirmation Code (Optional)</label>
                                    <input type="password" name="security_pin" class="form-control" placeholder="Enter security PIN">
                                </div>

                                <!-- Submit Buy Shares Button -->
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold rounded-3 shadow-sm" style="background: #2563eb; border: none;">
                                    <i class="bi bi-lightning-charge-fill me-1"></i> Buy Shares Now
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Documents Vault Accordion -->
                    <div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Project Documents</h5>
                            
                            @if($project->documents->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($project->documents as $doc)
                                        <a href="{{ route('marketplace.document.download', [$project, $doc->id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-pdf text-danger fs-4"></i>
                                                <div>
                                                    <h6 class="fw-semibold text-dark mb-0" style="font-size: 0.9rem;">{{ $doc->title }}</h6>
                                                    <span class="text-muted small">{{ ucfirst($doc->document_type) }}</span>
                                                </div>
                                            </div>
                                            @if($doc->is_restricted && (!$user || $user->kyc_status !== 'approved'))
                                                <span class="badge bg-secondary rounded-pill"><i class="bi bi-lock-fill me-1"></i> KYC Required</span>
                                            @else
                                                <i class="bi bi-download text-primary"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-0">Brochure and valuation files will be made available upon request.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Support & Help Cards -->
                    <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                        <h6 class="fw-bold text-dark mb-2">Need Help?</h6>
                        <p class="text-muted small mb-3">Our investment support team is available 24/7 to assist you.</p>
                        <div class="d-flex gap-2">
                            <a href="mailto:support@aurevia.com" class="btn btn-outline-primary btn-sm flex-fill fw-semibold"><i class="bi bi-envelope me-1"></i> Contact Support</a>
                            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm flex-fill fw-semibold"><i class="bi bi-question-circle me-1"></i> Ask Question</a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- AlpineJS Calculator Application Controller -->
<script>
function projectCalculatorApp() {
    return {
        selectedDuration: '14_days',
        sharesCount: 10,
        requiredShares: 10,
        sharePrice: {{ (float) ($project->share_price ?: 100) }},
        targetPct: 4.00,
        purchaseAmount: 1000.00,
        projectedEarnings: 40.00,
        completionValue: 1040.00,

        init() {
            this.updateCalculations();
        },

        selectDuration(key, reqShares, pct) {
            this.selectedDuration = key;
            this.requiredShares = reqShares;
            this.targetPct = pct;
            if (this.sharesCount < reqShares) {
                this.sharesCount = reqShares;
            }
            this.updateCalculations();
        },

        incrementShares() {
            this.sharesCount++;
            this.updateCalculations();
        },

        decrementShares() {
            if (this.sharesCount > 1) {
                this.sharesCount--;
                this.updateCalculations();
            }
        },

        updateCalculations() {
            if (this.sharesCount < 1) this.sharesCount = 1;
            this.purchaseAmount = this.sharesCount * this.sharePrice;
            this.projectedEarnings = (this.purchaseAmount * this.targetPct) / 100;
            this.completionValue = this.purchaseAmount + this.projectedEarnings;
        }
    }
}

function toggleSaveProject(projectId, btn) {
    fetch('/project-marketplace/' + projectId + '/save', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        location.reload();
    });
}

function shareProject(title, url) {
    if (navigator.share) {
        navigator.share({ title: title, url: url });
    } else {
        navigator.clipboard.writeText(url);
        alert('Link copied to clipboard!');
    }
}
</script>
@endsection
