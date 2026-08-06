<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Project;
use App\Models\ProjectDurationTier;
use App\Models\ProjectShareCycle;
use App\Models\ProjectDocument;
use App\Models\ProjectUpdate;
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
        $this->call(PaymentChannelSeeder::class);
        $this->call(SavedWithdrawalMethodSeeder::class);

        // Fetch seeded demo investor user
        $user = User::where('email', 'investor@radiantrealty.com')->first();
        if (!$user) {
            $user = User::first();
        }

        // Fetch seeded admin user
        $admin = User::where('role', 'admin')->first();

        // 1. Ensure user has starting wallet balance for demo
        if ($user) {
            $user->wallet_balance = 27500.00;
            $user->kyc_verified = true;
            $user->kyc_status = 'approved';
            $user->save();
        }

        // 2. Create Housing Properties (idempotent)
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

        // 3. Create Projects with Share Price and Duration Tiers
        $prj1 = Project::firstOrCreate(['title' => 'Suburban Garden Homes'], [
            'location' => 'Cebu City, Philippines',
            'category' => 'Multi-Family',
            'property_type' => 'Single-Family Homes',
            'bedrooms' => '3-4 Bedrooms',
            'bathrooms' => '2-3 Bathrooms',
            'land_size_sqm' => '2,800 m²',
            'building_size_sqm' => '120 - 150 m²',
            'total_units' => '20 Homes',
            'image_url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 350000.00,
            'share_price' => 100.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 16.00,
            'investment_duration_months' => 12,
            'funding_closing_date' => now()->addDays(362)->addHours(17),
            'rating' => 4.1,
            'description' => 'Gated community of 20 single-family homes with strong rental demand from families relocating to the metro area.',
            'developer_summary' => 'Developed by Aurevia Residential Builders, premier real estate developers with 15+ years track record in sustainable housing.',
            'purpose' => 'Construction and long-term rental monetization of 20 luxury family units.',
            'revenue_source' => 'Monthly rental income + capital appreciation upon completion.',
            'current_stage' => 'Under Construction',
            'status' => 'active',
            'is_verified' => true,
        ]);

        $prj2 = Project::firstOrCreate(['title' => 'Metro Business District Offices'], [
            'location' => 'Bonifacio Global City, Philippines',
            'category' => 'Commercial',
            'property_type' => 'Commercial Offices',
            'bedrooms' => 'N/A',
            'bathrooms' => 'Executive Restrooms',
            'land_size_sqm' => '4,500 m²',
            'building_size_sqm' => '12,000 m²',
            'total_units' => '45 Commercial Suites',
            'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 800000.00,
            'share_price' => 100.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 18.50,
            'investment_duration_months' => 12,
            'funding_closing_date' => now()->addDays(298)->addHours(21),
            'rating' => 4.3,
            'description' => 'Grade-A office tower in BGC with anchor tenants pre-committed. Stable long-term leases deliver consistent monthly distributions.',
            'status' => 'active',
            'is_verified' => true,
        ]);

        $prj3 = Project::firstOrCreate(['title' => 'Palm Grove Condo Estate'], [
            'location' => 'Davao City, Philippines',
            'category' => 'Luxury',
            'property_type' => 'Luxury Condos',
            'bedrooms' => '2-3 Bedrooms',
            'bathrooms' => '2 Bathrooms',
            'land_size_sqm' => '5,200 m²',
            'building_size_sqm' => '8,400 m²',
            'total_units' => '60 Luxury Units',
            'image_url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 600000.00,
            'share_price' => 100.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 20.00,
            'investment_duration_months' => 24,
            'funding_closing_date' => now()->addDays(333)->addHours(8),
            'rating' => 4.5,
            'description' => 'Beachfront condominium estate with resort amenities. Located in one of the fastest-growing tourist destinations in Southeast Asia.',
            'status' => 'active',
            'is_verified' => true,
        ]);

        $prj4 = Project::firstOrCreate(['title' => 'Horizon Towers Development'], [
            'location' => 'Makati City, Philippines',
            'category' => 'Residential',
            'property_type' => 'High-Rise Residences',
            'bedrooms' => '1-3 Bedrooms',
            'bathrooms' => '1-2 Bathrooms',
            'land_size_sqm' => '3,100 m²',
            'building_size_sqm' => '15,000 m²',
            'total_units' => '120 Apartments',
            'image_url' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?q=80&w=1000&auto=format&fit=crop',
            'target_amount' => 500000.00,
            'share_price' => 100.00,
            'minimum_investment' => 100.00,
            'expected_return_percentage' => 22.00,
            'investment_duration_months' => 18,
            'funding_closing_date' => now()->addDays(265)->addHours(14),
            'rating' => 4.7,
            'description' => 'A 42-story premium residential tower in the Makati CBD. Construction is 60% complete with units pre-selling fast.',
            'status' => 'active',
            'is_verified' => true,
        ]);

        // Seed Duration Tiers for each project
        $projects = [$prj1, $prj2, $prj3, $prj4];
        foreach ($projects as $project) {
            ProjectDurationTier::firstOrCreate(['project_id' => $project->id, 'duration_key' => '14_days'], [
                'duration_label' => '14 Days',
                'duration_days' => 14,
                'required_shares' => 10,
                'min_avc_value' => 1000.00,
                'target_earnings_pct' => 4.00,
                'is_popular' => true,
            ]);

            ProjectDurationTier::firstOrCreate(['project_id' => $project->id, 'duration_key' => '1_month'], [
                'duration_label' => '1 Month',
                'duration_days' => 30,
                'required_shares' => 25,
                'min_avc_value' => 2500.00,
                'target_earnings_pct' => 8.00,
                'is_popular' => false,
            ]);

            ProjectDurationTier::firstOrCreate(['project_id' => $project->id, 'duration_key' => '3_months'], [
                'duration_label' => '3 Months',
                'duration_days' => 90,
                'required_shares' => 50,
                'min_avc_value' => 5000.00,
                'target_earnings_pct' => 16.00,
                'is_popular' => false,
            ]);
        }

        // 4. Seed Demo Share Cycles for Investor User (Matches UI Mockup 3)
        if ($user) {
            // Active Cycle 1: Suburban Garden Homes (1 Month)
            ProjectShareCycle::firstOrCreate(['cycle_code' => 'CYC-SG-1001'], [
                'user_id' => $user->id,
                'project_id' => $prj1->id,
                'duration_key' => '1_month',
                'duration_label' => '1 Month',
                'duration_days' => 30,
                'shares_owned' => 25,
                'required_shares' => 25,
                'share_price' => 100.00,
                'total_purchase_amount' => 2500.00,
                'target_earnings_pct' => 8.00,
                'projected_earnings' => 200.00,
                'completion_value' => 2700.00,
                'status' => 'active',
                'purchased_at' => now()->subDays(11),
                'activated_at' => now()->subDays(11),
                'completion_date' => now()->addDays(19),
                'receipt_number' => 'RCP-20260720-9901',
            ]);

            // Active Cycle 2: Metro Business District Offices (14 Days)
            ProjectShareCycle::firstOrCreate(['cycle_code' => 'CYC-MB-1002'], [
                'user_id' => $user->id,
                'project_id' => $prj2->id,
                'duration_key' => '14_days',
                'duration_label' => '14 Days',
                'duration_days' => 14,
                'shares_owned' => 12,
                'required_shares' => 10,
                'share_price' => 100.00,
                'total_purchase_amount' => 1200.00,
                'target_earnings_pct' => 4.50,
                'projected_earnings' => 54.00,
                'completion_value' => 1254.00,
                'status' => 'active',
                'purchased_at' => now()->subDays(6),
                'activated_at' => now()->subDays(6),
                'completion_date' => now()->addDays(8),
                'receipt_number' => 'RCP-20260725-9902',
            ]);

            // Pending Activation Cycle: Palm Grove Condo Estate (3 Months)
            ProjectShareCycle::firstOrCreate(['cycle_code' => 'CYC-PG-1003'], [
                'user_id' => $user->id,
                'project_id' => $prj3->id,
                'duration_key' => '3_months',
                'duration_label' => '3 Months',
                'duration_days' => 90,
                'shares_owned' => 35,
                'required_shares' => 50,
                'share_price' => 100.00,
                'total_purchase_amount' => 3500.00,
                'target_earnings_pct' => 16.00,
                'projected_earnings' => 560.00,
                'completion_value' => 4060.00,
                'status' => 'pending_activation',
                'purchased_at' => now()->subDays(3),
                'activated_at' => null,
                'completion_date' => null,
                'receipt_number' => 'RCP-20260801-9903',
            ]);

            // Completed Cycle: Horizon Towers Development (14 Days)
            ProjectShareCycle::firstOrCreate(['cycle_code' => 'CYC-HT-1004'], [
                'user_id' => $user->id,
                'project_id' => $prj4->id,
                'duration_key' => '14_days',
                'duration_label' => '14 Days',
                'duration_days' => 14,
                'shares_owned' => 10,
                'required_shares' => 10,
                'share_price' => 100.00,
                'total_purchase_amount' => 1000.00,
                'target_earnings_pct' => 4.00,
                'projected_earnings' => 40.00,
                'completion_value' => 1040.00,
                'status' => 'completed',
                'purchased_at' => now()->subDays(25),
                'activated_at' => now()->subDays(25),
                'completion_date' => now()->subDays(11),
                'earnings_credited_at' => now()->subDays(11),
                'receipt_number' => 'RCP-20260613-9904',
            ]);
        }

        // 5. Create Documents and Updates for Projects
        ProjectDocument::firstOrCreate(['project_id' => $prj1->id, 'title' => 'Project Investment Brochure'], [
            'document_type' => 'brochure',
            'file_path' => 'documents/sample_brochure.pdf',
            'is_restricted' => false,
        ]);
        ProjectDocument::firstOrCreate(['project_id' => $prj1->id, 'title' => 'Share Purchase Terms & Agreement'], [
            'document_type' => 'agreement',
            'file_path' => 'documents/share_agreement.pdf',
            'is_restricted' => true,
        ]);

        ProjectUpdate::firstOrCreate(['project_id' => $prj1->id, 'title' => 'Foundation & Framing Stage Complete'], [
            'category' => 'Construction Progress',
            'content' => 'The structural framing for Phase 1 single-family homes is complete. Mechanical, electrical, and plumbing rough-ins are currently underway.',
            'published_at' => now()->subDays(5),
        ]);
    }
}
