@extends('layouts.main')

@section('title', 'Sign In | ' . site_name())

@section('content')
<section class="min-vh-100 d-flex align-items-center position-relative py-5" style="background: linear-gradient(135deg, rgba(13, 33, 55, 0.92) 0%, rgba(26, 60, 94, 0.88) 100%), url('{{ asset('frontend/images/hero_bg.png') }}') center/cover no-repeat;">
  <div class="container relative z-2 py-4">
    <div class="row align-items-center justify-content-center g-5">
      
      <!-- Left Column: Brand Showcase -->
      <div class="col-lg-6 text-white text-start d-none d-lg-block reveal-on-scroll delay-1">
        <span class="badge glass-panel text-white fw-bold px-3 py-2 rounded-pill mb-3" style="font-size: 0.85rem; letter-spacing: 1px;">WELCOME BACK</span>
        <h1 class="fw-bold display-4 mb-3" style="color: #ffffff; font-weight: 900; line-height: 1.2;">
          Manage Your <br><span style="color: #60a5fa; text-shadow: 0 0 20px rgba(96,165,250,0.4);">Property Portfolio.</span>
        </h1>
        <p class="text-white-50 fs-5 mb-4" style="line-height: 1.6;">
          Log in to track your co-ownership shares, view rental distribution payouts, and discover new high-yield investment properties.
        </p>

        <!-- Stats Highlights -->
        <div class="row g-3">
          <div class="col-6">
            <div class="p-3 rounded-4 glass-panel-dark">
              <h4 class="fw-bold text-primary mb-1">$1.05B+</h4>
              <small class="text-white-50">Transacted Real Estate</small>
            </div>
          </div>
          <div class="col-6">
            <div class="p-3 rounded-4 glass-panel-dark">
              <h4 class="fw-bold text-success mb-1">35,000+</h4>
              <small class="text-white-50">Active Global Investors</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Login Card -->
      <div class="col-lg-5 col-md-8 col-sm-10 reveal-on-scroll delay-2">
        <div class="card border-0 glass-card rounded-4 p-4 p-md-5 shadow-lg">
          <div class="text-center mb-4">
            <h3 class="fw-bold mb-1" style="color: #1a3c5e; font-weight: 800;">Sign In</h3>
            <p class="text-muted small">Enter your credentials to access your dashboard</p>
          </div>

          <!-- Session Status -->
          @if (session('status'))
            <div class="alert alert-success border-0 rounded-3 mb-4 small" role="alert">
              {{ session('status') }}
            </div>
          @endif

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

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold text-dark small">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input id="email" type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}" placeholder="name@example.com" required autofocus autocomplete="username">
              </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-semibold text-dark small mb-0">Password</label>
                @if (Route::has('password.request'))
                  <a href="{{ route('password.request') }}" class="text-primary text-decoration-none small fw-semibold">Forgot password?</a>
                @endif
              </div>
              <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input id="password" type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required autocomplete="current-password">
              </div>
            </div>

            <!-- Remember Me -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
              <label class="form-check-label text-muted small" for="remember_me">
                Keep me logged in
              </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow mb-3" style="background-color: #2756fd; border-radius: 8px; font-size: 1rem;">
              Sign In to Account
            </button>

            <!-- Register Link -->
            <div class="text-center pt-2 border-top">
              <span class="text-muted small">Don't have an account?</span>
              <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none small ms-1">Create Account</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
