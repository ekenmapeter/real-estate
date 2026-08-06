<?php

namespace App\Http\Controllers;

use App\Models\PaymentChannel;
use Illuminate\Http\Request;

class AdminPaymentChannelController extends Controller
{
    public function index()
    {
        $channels = PaymentChannel::orderBy('id', 'desc')->get();
        return view('admin.payment-channels.index', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'method_key' => 'required|string|in:bank_transfer,credit_card,wire_transfer,crypto',
            'channel_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        PaymentChannel::create($request->all());

        return redirect()->route('admin.payment-channels.index')->with('success', 'New payment channel created successfully!');
    }

    public function update(Request $request, PaymentChannel $channel)
    {
        $request->validate([
            'channel_name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        $channel->update($request->all());

        return redirect()->route('admin.payment-channels.index')->with('success', 'Payment channel updated successfully!');
    }

    public function destroy(PaymentChannel $channel)
    {
        $channel->delete();
        return redirect()->route('admin.payment-channels.index')->with('success', 'Payment channel deleted.');
    }
}
