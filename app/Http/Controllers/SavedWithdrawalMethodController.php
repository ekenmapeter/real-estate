<?php

namespace App\Http\Controllers;

use App\Models\SavedWithdrawalMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedWithdrawalMethodController extends Controller
{
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'method_key' => 'required|string|in:bank_transfer,mobile_wallet,wire_transfer,crypto',
            'title' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
        ]);

        $accountNumber = $request->account_number ?: $request->wallet_address;
        $masked = SavedWithdrawalMethod::maskNumber($accountNumber);

        SavedWithdrawalMethod::create([
            'user_id' => $user->id,
            'method_key' => $request->method_key,
            'title' => $request->title,
            'account_name' => $request->account_name,
            'bank_or_provider' => $request->bank_or_provider,
            'account_number' => $accountNumber,
            'masked_account_number' => $masked,
            'account_type' => $request->account_type,
            'swift_bic' => $request->swift_bic,
            'country' => $request->country ?? 'Philippines',
            'currency' => $request->currency ?? 'PHP',
            'crypto_asset' => $request->crypto_asset,
            'crypto_network' => $request->crypto_network,
            'wallet_address' => $request->wallet_address,
        ]);

        return redirect()->back()->with('success', 'Saved withdrawal method added successfully!');
    }

    public function destroy(SavedWithdrawalMethod $savedMethod)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($savedMethod->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $savedMethod->delete();
        return redirect()->back()->with('success', 'Saved withdrawal method removed.');
    }

    public function setDefault(SavedWithdrawalMethod $savedMethod)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($savedMethod->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        SavedWithdrawalMethod::where('user_id', $user->id)->update(['is_default' => false]);
        $savedMethod->is_default = true;
        $savedMethod->save();

        return redirect()->back()->with('success', $savedMethod->title . ' set as default payout destination.');
    }
}
