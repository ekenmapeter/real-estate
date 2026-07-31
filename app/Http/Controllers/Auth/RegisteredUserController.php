<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ReferralBonusMail;
use App\Mail\ReferralNotificationMail;
use App\Mail\WelcomeMail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $referrerId = null;
        if ($ref = $request->input('ref')) {
            $referrer = User::where('affiliate_code', $ref)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referred_by' => $referrerId,
        ]);

        event(new Registered($user));

        if ($referrerId) {
            $bonusAmount = 10.00;
            $referrer = User::find($referrerId);

            if ($referrer) {
                $referrer->wallet_balance = ($referrer->wallet_balance ?? 0) + $bonusAmount;
                $referrer->affiliate_earnings = ($referrer->affiliate_earnings ?? 0) + $bonusAmount;
                $referrer->save();

                Transaction::create([
                    'user_id' => $referrer->id,
                    'type' => 'affiliate_earning',
                    'amount' => $bonusAmount,
                    'reference' => 'REF-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'description' => 'Referral bonus for referring ' . $user->name,
                    'status' => 'completed',
                ]);

                Mail::to($referrer->email)->send(new ReferralBonusMail($referrer, $user, $bonusAmount));

                $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@radiantrealty.com');
                Mail::to($adminEmail)->send(new ReferralNotificationMail($referrer, $user, $bonusAmount));
            }
        }

        Mail::to($user->email)->send(new WelcomeMail($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
