<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Investment;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        // Fetch seeded demo investor user
        $user = User::where('email', 'investor@radiantrealty.com')->first();
        if (!$user) {
            $user = User::first();
        }

        // Fetch seeded admin user
        $admin = User::where('role', 'admin')->first();

        // 3. Create Housing Properties
        $p1 = Property::create([
            'title' => 'Aura Grand Penthouse Suites',
            'location' => 'Manhattan, New York',
            'category' => 'Luxury Residential',
            'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop',
            'price_per_share' => 500.00,
            'total_shares' => 1000,
            'available_shares' => 420,
            'roi_percentage' => 24.50,
            'investment_duration_months' => 12,
            'description' => 'Ultra-luxury penthouse co-ownership in prime Manhattan with high quarterly rental yields and guaranteed asset appreciation.',
            'status' => 'active',
        ]);

        $p2 = Property::create([
            'title' => 'Silicon Valley Eco Tech Hub',
            'location' => 'San Jose, California',
            'category' => 'Commercial',
            'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop',
            'price_per_share' => 250.00,
            'total_shares' => 2000,
            'available_shares' => 1100,
            'roi_percentage' => 19.20,
            'investment_duration_months' => 18,
            'description' => 'Leased commercial office complex housing Fortune 500 tech tenants with long-term rental contracts.',
            'status' => 'active',
        ]);

        $p3 = Property::create([
            'title' => 'Azure Coast Beachfront Resort',
            'location' => 'Miami Beach, Florida',
            'category' => 'Beachfront Villa',
            'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1000&auto=format&fit=crop',
            'price_per_share' => 1000.00,
            'total_shares' => 500,
            'available_shares' => 180,
            'roi_percentage' => 28.00,
            'investment_duration_months' => 24,
            'description' => 'Exclusive oceanfront vacation villa offering high holiday rental revenue and tax-advantaged property distributions.',
            'status' => 'active',
        ]);

        $p4 = Property::create([
            'title' => 'The Horizon Skyline Residences',
            'location' => 'Austin, Texas',
            'category' => 'Apartments',
            'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1000&auto=format&fit=crop',
            'price_per_share' => 300.00,
            'total_shares' => 1500,
            'available_shares' => 950,
            'roi_percentage' => 17.80,
            'investment_duration_months' => 12,
            'description' => 'Modern high-density residential towers located in downtown Austin’s fast-growing financial corridor.',
            'status' => 'active',
        ]);

        // 4. Create Active Investments for User
        $inv1 = Investment::create([
            'user_id' => $user->id,
            'property_id' => $p1->id,
            'shares_bought' => 20,
            'total_amount' => 10000.00,
            'expected_roi_amount' => 2450.00,
            'roi_earned' => 612.50,
            'status' => 'active',
        ]);

        $inv2 = Investment::create([
            'user_id' => $user->id,
            'property_id' => $p2->id,
            'shares_bought' => 40,
            'total_amount' => 10000.00,
            'expected_roi_amount' => 1920.00,
            'roi_earned' => 480.00,
            'status' => 'active',
        ]);

        // 5. Seed Deposits
        Deposit::create([
            'user_id' => $user->id,
            'deposit_code' => 'DEP-99401',
            'amount' => 25000.00,
            'payment_method' => 'bank_transfer',
            'details' => 'Chase Bank Wire Transfer',
            'reference_id' => 'TXN-BANK-883920',
            'status' => 'approved',
        ]);

        Deposit::create([
            'user_id' => $user->id,
            'deposit_code' => 'DEP-99402',
            'amount' => 20000.00,
            'payment_method' => 'crypto',
            'details' => 'USDT (TRC20): TxHash 0x8f2a...4b1c',
            'reference_id' => '0x8f2a9914b1c3e7',
            'status' => 'approved',
        ]);

        Deposit::create([
            'user_id' => $user->id,
            'deposit_code' => 'DEP-99403',
            'amount' => 15000.00,
            'payment_method' => 'credit_card',
            'details' => 'Visa Ending in 4242',
            'reference_id' => 'VISA-88271',
            'status' => 'approved',
        ]);

        Deposit::create([
            'user_id' => $user->id,
            'deposit_code' => 'DEP-99404',
            'amount' => 10000.00,
            'payment_method' => 'wire_transfer',
            'details' => 'Federal International Wire',
            'reference_id' => 'WIRE-99201',
            'status' => 'pending',
        ]);

        // 6. Seed Withdrawals
        Withdrawal::create([
            'user_id' => $user->id,
            'withdrawal_code' => 'WTH-33101',
            'amount' => 3000.00,
            'withdrawal_method' => 'crypto',
            'account_details' => 'USDT Wallet: TEvXn9942a10sK9921',
            'status' => 'approved',
        ]);

        Withdrawal::create([
            'user_id' => $user->id,
            'withdrawal_code' => 'WTH-33102',
            'amount' => 1500.00,
            'withdrawal_method' => 'bank_transfer',
            'account_details' => 'Bank: Bank of America, Acc: ****6789',
            'status' => 'pending',
        ]);

        // 7. Seed Transactions
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 25000.00,
            'reference' => 'DEP-99401',
            'description' => 'Bank Transfer Deposit Approved',
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 20000.00,
            'reference' => 'DEP-99402',
            'description' => 'USDT Cryptocurrency Deposit Approved',
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'property_investment',
            'amount' => 10000.00,
            'reference' => 'INV-AURA-20',
            'description' => 'Purchased 20 Shares in Aura Grand Penthouse Suites',
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'property_investment',
            'amount' => 10000.00,
            'reference' => 'INV-SILICON-40',
            'description' => 'Purchased 40 Shares in Silicon Valley Eco Tech Hub',
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'roi_payout',
            'amount' => 1092.50,
            'reference' => 'ROI-Q2-2026',
            'description' => 'Quarterly ROI Yield Distribution',
            'status' => 'completed',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'affiliate_earning',
            'amount' => 1450.00,
            'reference' => 'AFF-COMMISSION',
            'description' => 'Affiliate Referral Reward Commission',
            'status' => 'completed',
        ]);
    }
}
