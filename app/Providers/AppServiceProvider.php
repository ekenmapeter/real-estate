<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! function_exists('site_name')) {
            require_once app_path('helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(($request->input('email') ?: '').'|'.$request->ip())
                ->response(function () {
                    return back()->withInput()->withErrors([
                        'email' => 'Too many login attempts. Please wait a minute and try again.',
                    ]);
                });
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())
                ->response(function () {
                    return back()->withInput()->withErrors([
                        'name' => 'Too many registration attempts. Please wait a minute and try again.',
                    ]);
                });
        });

        RateLimiter::for('password', function (Request $request) {
            return Limit::perMinute(3)->by(($request->input('email') ?: '').'|'.$request->ip())
                ->response(function () {
                    return back()->withInput()->withErrors([
                        'email' => 'Too many requests. Please wait a minute and try again.',
                    ]);
                });
        });

        RateLimiter::for('forms', function (Request $request) {
            $key = $request->user() ? (string) $request->user()->id : (string) $request->ip();

            return Limit::perMinute(20)->by($key)
                ->response(function () {
                    return back()->with('error', 'Too many submissions. Please wait a minute and try again.');
                });
        });

        RateLimiter::for('admin', function (Request $request) {
            $key = $request->user() ? (string) $request->user()->id : (string) $request->ip();

            return Limit::perMinute(60)->by($key)
                ->response(function () {
                    return back()->with('error', 'Too many requests. Please wait a minute and try again.');
                });
        });
    }
}
