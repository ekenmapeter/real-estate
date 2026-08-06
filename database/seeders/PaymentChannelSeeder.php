<?php

namespace Database\Seeders;

use App\Models\PaymentChannel;
use Illuminate\Database\Seeder;

class PaymentChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'method_key' => 'bank_transfer',
                'channel_name' => 'Local Bank Deposit (BDO / BPI / UnionBank)',
                'account_name' => 'Aurevia Real Estate Services Inc.',
                'bank_or_provider' => 'BDO Unibank',
                'account_number' => '0081-2299-4410',
                'country' => 'Philippines',
                'currency' => 'PHP',
                'min_deposit_amount' => 10.00,
                'max_deposit_amount' => 100000.00,
                'daily_limit' => 500000.00,
                'processing_info' => '15 - 30 Minutes',
                'status' => 'active',
                'visibility' => 'request_only',
            ],
            [
                'method_key' => 'credit_card',
                'channel_name' => 'Credit / Debit Card (Visa, Mastercard, AMEX)',
                'bank_or_provider' => 'Stripe Gateway',
                'country' => 'Global',
                'currency' => 'USD',
                'min_deposit_amount' => 20.00,
                'max_deposit_amount' => 10000.00,
                'daily_limit' => 25000.00,
                'processing_info' => 'Instant Credit',
                'status' => 'active',
                'visibility' => 'request_only',
            ],
            [
                'method_key' => 'wire_transfer',
                'channel_name' => 'International Wire Transfer (SWIFT / IBAN)',
                'account_name' => 'Aurevia Global Property Corp',
                'bank_or_provider' => 'JPMorgan Chase Bank, N.A.',
                'account_number' => '99482710492',
                'swift_bic' => 'CHASUS33XXX',
                'iban' => 'US89CHAS99482710492',
                'country' => 'United States',
                'currency' => 'USD',
                'min_deposit_amount' => 100.00,
                'max_deposit_amount' => 500000.00,
                'daily_limit' => 1000000.00,
                'processing_info' => '1 - 3 Business Days',
                'status' => 'active',
                'visibility' => 'request_only',
            ],
            [
                'method_key' => 'crypto',
                'channel_name' => 'USDT (TRC-20) Crypto Wallet',
                'wallet_asset' => 'USDT',
                'blockchain_network' => 'TRC-20',
                'wallet_address' => 'TYD3xX9Pq4Nm8kZ1v7L5wQ2mS8aR4bE9nF',
                'min_deposit_amount' => 10.00,
                'max_deposit_amount' => 250000.00,
                'daily_limit' => 1000000.00,
                'processing_info' => '12 Blockchain Confirmations',
                'status' => 'active',
                'visibility' => 'request_only',
            ],
            [
                'method_key' => 'crypto',
                'channel_name' => 'Bitcoin (BTC Network)',
                'wallet_asset' => 'BTC',
                'blockchain_network' => 'BTC Network',
                'wallet_address' => 'bc1q9v8h2g5k7m3n4p6x8r0t2y4u6w8z1a3c5e7g9',
                'min_deposit_amount' => 50.00,
                'max_deposit_amount' => 500000.00,
                'daily_limit' => 2000000.00,
                'processing_info' => '3 Network Confirmations',
                'status' => 'active',
                'visibility' => 'request_only',
            ],
        ];

        foreach ($channels as $data) {
            PaymentChannel::firstOrCreate(
                ['channel_name' => $data['channel_name']],
                $data
            );
        }
    }
}
