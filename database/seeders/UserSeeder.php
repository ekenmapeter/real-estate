<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the user login database seeds.
     */
    public function run(): void
    {
        // 1. Regular Investor Account
        User::updateOrCreate(
            ['email' => 'investor@radiantrealty.com'],
            [
                'name' => 'Alexander Wright',
                'password' => Hash::make('password'),
                'account_id' => 'RDR-884920',
                'wallet_balance' => 45250.00,
                'role' => 'user',
                'affiliate_code' => 'RAD8849',
                'transaction_pin' => Hash::make('8849'),
                'affiliate_earnings' => 1450.00,
            ]
        );

        // 2. Platform Admin Account
        User::updateOrCreate(
            ['email' => 'admin@radiantrealty.com'],
            [
                'name' => 'Ekenma Peter (Admin)',
                'password' => Hash::make('password'),
                'account_id' => 'RDR-ADM001',
                'wallet_balance' => 150000.00,
                'role' => 'admin',
                'affiliate_code' => 'ADMIN01',
                'transaction_pin' => Hash::make('8849'),
                'affiliate_earnings' => 0.00,
            ]
        );

        // 3. Additional Demo Investor Account
        User::updateOrCreate(
            ['email' => 'sarah.jenkins@radiantrealty.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'account_id' => 'RDR-194028',
                'wallet_balance' => 28500.00,
                'role' => 'user',
                'affiliate_code' => 'RAD1940',
                'transaction_pin' => Hash::make('8849'),
                'affiliate_earnings' => 600.00,
            ]
        );
    }
}
