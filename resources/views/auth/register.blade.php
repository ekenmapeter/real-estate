@extends('layouts.main')

@section('title', 'Create Account | ' . site_name())

@section('content')
<section class="min-vh-100 d-flex align-items-center position-relative py-5" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat;">
  <div class="container relative z-2 py-4">
    <div class="row align-items-center justify-content-center g-5">
      
      <!-- Left Column: Brand Showcase -->
      <div class="col-lg-6 text-white text-start d-none d-lg-block reveal-on-scroll delay-1">
        <span class="badge glass-panel text-white fw-bold px-3 py-2 rounded-pill mb-3" style="font-size: 0.85rem; letter-spacing: 1px;">CREATE ACCOUNT</span>
        <h1 class="fw-bold display-4 mb-3" style="color: #ffffff; font-weight: 900; line-height: 1.2;">
          Start Your <br><span style="color: #34d399; text-shadow: 0 0 20px rgba(52,211,153,0.4);">Co-Ownership Journey.</span>
        </h1>
        <p class="text-white-50 fs-5 mb-4" style="line-height: 1.6;">
          Join over 35,000 global investors earning quarterly dividends on luxury real estate assets with low $1,250 entry points.
        </p>

        <!-- Perks List -->
        <div class="space-y-3">
          <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-4 glass-panel-dark">
            <i class="bi bi-shield-check text-success fs-3"></i>
            <div>
              <h6 class="text-white mb-0 fw-bold">Escrow Protected Funds</h6>
              <small class="text-white-50">Audited and managed by top-tier banking partners globally.</small>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-4 glass-panel-dark">
            <i class="bi bi-cash-stack text-warning fs-3"></i>
            <div>
              <h6 class="text-white mb-0 fw-bold">10% - 30% Affiliate Commissions</h6>
              <small class="text-white-50">Earn steady income as an affiliate or verified agent.</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Register Card -->
      <div class="col-lg-5 col-md-8 col-sm-10 reveal-on-scroll delay-2">
        <div class="card border-0 glass-card rounded-4 p-4 p-md-5 shadow-lg">
          <div class="text-center mb-4">
            <h3 class="fw-bold mb-1" style="color: #1a3c5e; font-weight: 800;">Register</h3>
            <p class="text-muted small">Create your free account to get started</p>
          </div>

          <!-- Validation Errors -->
          @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4 small">
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            @if(request('ref'))
                <input type="hidden" name="ref" value="{{ request('ref') }}">
            @endif

            <!-- User Name -->
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold text-dark small">User name</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                <input id="name" type="text" name="name" class="form-control border-start-0 ps-0" value="{{ old('name') }}" placeholder="User name" required autofocus autocomplete="name">
              </div>
            </div>

            <!-- Email Address -->
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold text-dark small">Email address</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input id="email" type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}" placeholder="Email address" required autocomplete="username">
              </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold text-dark small">Password</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input id="password" type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Your password" required autocomplete="new-password">
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold text-dark small">Confirm password</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Confirm password" required autocomplete="new-password">
              </div>
            </div>

            <!-- Captcha Section -->
            <div class="mb-4">
              <label for="captcha_input" class="form-label fw-semibold text-dark small">Enter Captcha</label>
              <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-dark text-white fw-bold fs-5 px-3 py-2 rounded-3 text-center flex-grow-1 user-select-none" id="captchaBox" style="letter-spacing: 5px; font-family: monospace; background: linear-gradient(45deg, #1e293b, #0f172a);">
                  7K9m2P
                </div>
                <button type="button" class="btn btn-outline-secondary p-2 rounded-3" onclick="refreshCaptcha()" title="Refresh Captcha">
                  <i class="bi bi-arrow-clockwise fs-5"></i>
                </button>
              </div>
              <input id="captcha_input" type="text" name="captcha" class="form-control" placeholder="Enter Captcha" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow mb-4" style="background-color: #2756fd; border-radius: 8px; font-size: 1rem;">
              Sign Up
            </button>

            <!-- Login Link -->
            <div class="text-center mb-4">
              <span class="text-muted small">Already have an account?</span>
              <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none small ms-1">Sign In</a>
            </div>

           

          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Captcha Generator Script -->
<script>
  function generateCaptcha() {
    const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let captcha = '';
    for (let i = 0; i < 6; i++) {
      captcha += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return captcha;
  }

  function refreshCaptcha() {
    const box = document.getElementById('captchaBox');
    if (box) {
      box.innerText = generateCaptcha();
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    refreshCaptcha();
  });
</script>
@endsection
