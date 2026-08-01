<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Project;
use App\Models\ProjectInvestment;
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

        // 3. Create Housing Properties (idempotent)
        $p1 = Property::firstOrCreate(['title' => 'Aura Grand Penthouse Suites'], [
            'location' => 'Manhattan, New York',
            'category' => 'Luxury Residential',
            'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop',
            'price' => 850000.00,
            'price_per_share' => 500.00,
            'total_shares' => 1000,
            'available_shares' => 420,
            'roi_percentage' => 24.50,
            'investment_duration_months' => 12,
            'description' => 'Ultra-luxury penthouse co-ownership in prime Manhattan with high quarterly rental yields and guaranteed asset appreciation.',
            'status' => 'active',
        ]);

        $p2 = Property::firstOrCreate(['title' => 'Silicon Valley Eco Tech Hub'], [
            'location' => 'San Jose, California',
            'category' => 'Commercial',
            'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop',
            'price' => 1250000.00,
            'price_per_share' => 250.00,
            'total_shares' => 2000,
            'available_shares' => 1100,
            'roi_percentage' => 19.20,
            'investment_duration_months' => 18,
            'description' => 'Leased commercial office complex housing Fortune 500 tech tenants with long-term rental contracts.',
            'status' => 'active',
        ]);

        $p3 = Property::firstOrCreate(['title' => 'Azure Coast Beachfront Resort'], [
            'location' => 'Miami Beach, Florida',
            'category' => 'Beachfront Villa',
            'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1000&auto=format&fit=crop',
            'price' => 2400000.00,
            'price_per_share' => 1000.00,
            'total_shares' => 500,
            'available_shares' => 180,
            'roi_percentage' => 28.00,
            'investment_duration_months' => 24,
            'description' => 'Exclusive oceanfront vacation villa offering high holiday rental revenue and tax-advantaged property distributions.',
            'status' => 'active',
        ]);

        $p4 = Property::firstOrCreate(['title' => 'The Horizon Skyline Residences'], [
            'location' => 'Austin, Texas',
            'category' => 'Apartments',
            'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1000&auto=format&fit=crop',
            'price' => 780000.00,
            'price_per_share' => 300.00,
            'total_shares' => 1500,
            'available_shares' => 950,
            'roi_percentage' => 17.80,
            'investment_duration_months' => 12,
            'description' => 'Modern high-density residential towers located in downtown Austin’s fast-growing financial corridor.',
            'status' => 'active',
        ]);

        // 3b. Create Investment Projects (idempotent)
        $projectsData = [
            ['title' => 'Horizon Towers Development', 'rating' => 4.7],
            ['title' => 'Palm Grove Condo Estate', 'rating' => 4.5],
            ['title' => 'Metro Business District Offices', 'rating' => 4.3],
            ['title' => 'Suburban Garden Homes', 'rating' => 4.1],
        ];
        foreach ($projectsData as $pData) {
            Project::where('title', $pData['title'])->where('rating', 0)->update(['rating' => $pData['rating']]);
        }

        $prj1 = Project::firstOrCreate(['title' => 'Horizon Towers Development'], [
            'location' => 'Makati City, Philippines',
            'category' => 'Residential',
            'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 500000.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 22.00,
            'investment_duration_months' => 18,
            'rating' => 4.7,
            'description' => 'A 42-story premium residential tower in the Makati CBD. Construction is 60% complete with units pre-selling fast. Early investors benefit from the full appreciation cycle.',
            'status' => 'active',
        ]);

        $prj2 = Project::firstOrCreate(['title' => 'Palm Grove Condo Estate'], [
            'location' => 'Davao City, Philippines',
            'category' => 'Luxury',
            'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 250000.00,
            'minimum_investment' => 50.00,
            'expected_return_percentage' => 28.00,
            'investment_duration_months' => 24,
            'rating' => 4.5,
            'description' => 'Beachfront condominium estate with resort amenities. Located in one of the fastest-growing tourist destinations in Southeast Asia.',
            'status' => 'active',
        ]);

        $prj3 = Project::firstOrCreate(['title' => 'Metro Business District Offices'], [
            'location' => 'Bonifacio Global City, Philippines',
            'category' => 'Commercial',
            'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 800000.00,
            'minimum_investment' => 200.00,
            'expected_return_percentage' => 18.50,
            'investment_duration_months' => 12,
            'rating' => 4.3,
            'description' => 'Grade-A office tower in BGC with anchor tenants pre-committed. Stable long-term leases deliver consistent monthly distributions.',
            'status' => 'active',
        ]);

        $prj4 = Project::firstOrCreate(['title' => 'Suburban Garden Homes'], [
            'location' => 'Cebu City, Philippines',
            'category' => 'Multi-Family',
            'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 350000.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 16.00,
            'investment_duration_months' => 12,
            'rating' => 4.1,
            'description' => 'Gated community of 120 single-family homes with strong rental demand from families relocating to the metro area.',
            'status' => 'active',
        ]);

        // 3c. Seed an active project investment for the demo user (idempotent)
        ProjectInvestment::firstOrCreate(
            ['user_id' => $user->id, 'project_id' => $prj1->id],
            [
                'amount' => 2000.00,
                'expected_roi_amount' => 440.00,
                'roi_earned' => 110.00,
                'status' => 'active',
            ]
        );

        // 4. Create Active Investments for User (idempotent)
        $inv1 = Investment::firstOrCreate(
            ['user_id' => $user->id, 'property_id' => $p1->id],
            [
                'shares_bought' => 20,
                'total_amount' => 10000.00,
                'expected_roi_amount' => 2450.00,
                'roi_earned' => 612.50,
                'status' => 'active',
            ]
        );

        $inv2 = Investment::firstOrCreate(
            ['user_id' => $user->id, 'property_id' => $p2->id],
            [
                'shares_bought' => 40,
                'total_amount' => 10000.00,
                'expected_roi_amount' => 1920.00,
                'roi_earned' => 480.00,
                'status' => 'active',
            ]
        );

        // 5. Seed Deposits (idempotent)
        Deposit::firstOrCreate(['deposit_code' => 'DEP-99401'], [
            'user_id' => $user->id,
            'amount' => 25000.00,
            'payment_method' => 'bank_transfer',
            'details' => 'Chase Bank Wire Transfer',
            'reference_id' => 'TXN-BANK-883920',
            'status' => 'approved',
        ]);

        Deposit::firstOrCreate(['deposit_code' => 'DEP-99402'], [
            'user_id' => $user->id,
            'amount' => 20000.00,
            'payment_method' => 'crypto',
            'details' => 'USDT (TRC20): TxHash 0x8f2a...4b1c',
            'reference_id' => '0x8f2a9914b1c3e7',
            'status' => 'approved',
        ]);

        Deposit::firstOrCreate(['deposit_code' => 'DEP-99403'], [
            'user_id' => $user->id,
            'amount' => 15000.00,
            'payment_method' => 'credit_card',
            'details' => 'Visa Ending in 4242',
            'reference_id' => 'VISA-88271',
            'status' => 'approved',
        ]);

        Deposit::firstOrCreate(['deposit_code' => 'DEP-99404'], [
            'user_id' => $user->id,
            'amount' => 10000.00,
            'payment_method' => 'wire_transfer',
            'details' => 'Federal International Wire',
            'reference_id' => 'WIRE-99201',
            'status' => 'pending',
        ]);

        // 6. Seed Withdrawals (idempotent)
        Withdrawal::firstOrCreate(['withdrawal_code' => 'WTH-33101'], [
            'user_id' => $user->id,
            'amount' => 3000.00,
            'withdrawal_method' => 'crypto',
            'account_details' => 'USDT Wallet: TEvXn9942a10sK9921',
            'status' => 'approved',
        ]);

        Withdrawal::firstOrCreate(['withdrawal_code' => 'WTH-33102'], [
            'user_id' => $user->id,
            'amount' => 1500.00,
            'withdrawal_method' => 'bank_transfer',
            'account_details' => 'Bank: Bank of America, Acc: ****6789',
            'status' => 'pending',
        ]);

        // 7. Seed Transactions (idempotent)
        Transaction::firstOrCreate(['reference' => 'DEP-99401'], [
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 25000.00,
            'description' => 'Bank Transfer Deposit Approved',
            'status' => 'completed',
        ]);

        Transaction::firstOrCreate(['reference' => 'DEP-99402'], [
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 20000.00,
            'description' => 'USDT Cryptocurrency Deposit Approved',
            'status' => 'completed',
        ]);

        Transaction::firstOrCreate(['reference' => 'INV-AURA-20'], [
            'user_id' => $user->id,
            'type' => 'property_investment',
            'amount' => 10000.00,
            'description' => 'Purchased 20 Shares in Aura Grand Penthouse Suites',
            'status' => 'completed',
        ]);

        Transaction::firstOrCreate(['reference' => 'INV-SILICON-40'], [
            'user_id' => $user->id,
            'type' => 'property_investment',
            'amount' => 10000.00,
            'description' => 'Purchased 40 Shares in Silicon Valley Eco Tech Hub',
            'status' => 'completed',
        ]);

        Transaction::firstOrCreate(['reference' => 'ROI-Q2-2026'], [
            'user_id' => $user->id,
            'type' => 'roi_payout',
            'amount' => 1092.50,
            'description' => 'Quarterly ROI Yield Distribution',
            'status' => 'completed',
        ]);

        Transaction::firstOrCreate(['reference' => 'AFF-COMMISSION'], [
            'user_id' => $user->id,
            'type' => 'affiliate_earning',
            'amount' => 1450.00,
            'description' => 'Affiliate Referral Reward Commission',
            'status' => 'completed',
        ]);
    }
}
