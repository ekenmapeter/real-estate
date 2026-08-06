<?php

namespace Database\Seeders;

use App\Models\SavedWithdrawalMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class SavedWithdrawalMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        if (!$user) return;

        $methods = [
            [
                'user_id' => $user->id,
                'method_key' => 'bank_transfer',
                'title' => 'BDO •••• 9012',
                'account_name' => $user->name,
                'bank_or_provider' => 'BDO (Bank of the Philippine Islands)',
                'account_number' => '123456789012',
                'masked_account_number' => '•••• 9012',
                'account_type' => 'Savings',
                'country' => 'Philippines',
                'currency' => 'PHP',
                'is_default' => true,
            ],
            [
                'user_id' => $user->id,
                'method_key' => 'mobile_wallet',
                'title' => 'GCash •••• 4491',
                'account_name' => $user->name,
                'bank_or_provider' => 'GCash Wallet',
                'account_number' => '09171234491',
                'masked_account_number' => '•••• 4491',
                'country' => 'Philippines',
                'currency' => 'PHP',
                'is_default' => false,
            ],
            [
                'user_id' => $user->id,
                'method_key' => 'crypto',
                'title' => 'USDT (TRC-20) •••• 5V6b',
                'account_name' => 'Wallet Address',
                'bank_or_provider' => 'USDT (TRC-20)',
                'crypto_asset' => 'USDT',
                'crypto_network' => 'TRC-20',
                'wallet_address' => 'TYd1kL9nEXTP2q4W5nEWe3h8K9Ln5V6b',
                'masked_account_number' => '•••• 5V6b',
                'is_default' => false,
            ],
            [
                'user_id' => $user->id,
                'method_key' => 'wire_transfer',
                'title' => 'Chase Bank •••• 7890',
                'account_name' => $user->name,
                'bank_or_provider' => 'Chase Bank',
                'account_number' => '9948271047890',
                'masked_account_number' => '•••• 7890',
                'swift_bic' => 'CHASUS33XXX',
                'country' => 'United States',
                'currency' => 'USD',
                'is_default' => false,
            ],
        ];

        foreach ($methods as $data) {
            SavedWithdrawalMethod::firstOrCreate(
                ['user_id' => $data['user_id'], 'title' => $data['title']],
                $data
            );
        }
    }
}
