<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyConversation;
use App\Models\PropertyInquiry;
use App\Models\PropertyReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PropertiesMarketplaceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $rep = User::firstOrCreate(
            ['email' => 'demo.agent@aurevia.test'],
            [
                'name' => 'Sarah Mitchell',
                'password' => Hash::make('password'),
                'role' => 'user',
                'rep_type' => 'agent',
                'rep_status' => 'verified',
                'rep_verified_at' => now(),
                'kyc_status' => 'approved',
                'kyc_verified' => true,
            ]
        );

        $owner = User::firstOrCreate(
            ['email' => 'demo.owner@aurevia.test'],
            [
                'name' => 'David Okafor',
                'password' => Hash::make('password'),
                'role' => 'user',
                'rep_type' => 'owner',
                'rep_status' => 'verified',
                'rep_verified_at' => now(),
                'kyc_status' => 'approved',
                'kyc_verified' => true,
            ]
        );

        $placeholder = asset('images/property-placeholder.jpg');
        $hero = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200';

        $sample = [
            [
                'title' => 'Azure Coast Beachfront Villa',
                'category' => 'Beachfront', 'listing_type' => 'sale', 'price' => 1250000,
                'country' => 'United Arab Emirates', 'city' => 'Dubai', 'state' => 'Dubai',
                'bedrooms' => 5, 'bathrooms' => 6, 'property_size' => 540, 'land_size' => 900,
                'parking' => '4 covered spaces', 'ownership_type' => 'freehold',
                'amenities' => ['Pool', 'Gym', 'Parking', 'Sea View', 'Security', 'Smart Home'],
                'is_verified' => true, 'status' => 'published',
            ],
            [
                'title' => 'Skyline Marina Penthouse',
                'category' => 'Luxury', 'listing_type' => 'rent', 'monthly_rent' => 8500, 'security_deposit' => 17000,
                'country' => 'United Arab Emirates', 'city' => 'Dubai Marina',
                'bedrooms' => 3, 'bathrooms' => 4, 'property_size' => 320,
                'parking' => '2 covered spaces', 'ownership_type' => 'strata',
                'amenities' => ['Elevator', 'Gym', 'Balcony', 'Sea View', 'Air Conditioning'],
                'is_verified' => true, 'status' => 'published',
            ],
            [
                'title' => 'Oakwood Family Residence',
                'category' => 'Residential', 'listing_type' => 'sale', 'price' => 420000,
                'country' => 'United States', 'state' => 'Texas', 'city' => 'Austin',
                'bedrooms' => 4, 'bathrooms' => 3, 'property_size' => 280, 'land_size' => 610,
                'parking' => '2-car garage', 'ownership_type' => 'freehold',
                'amenities' => ['Garden', 'Parking', 'Security'],
                'is_verified' => false, 'status' => 'submitted',
            ],
            [
                'title' => 'Costa Verde Vacation Home',
                'category' => 'Residential', 'listing_type' => 'sale', 'price' => 680000,
                'country' => 'Spain', 'state' => 'Andalusia', 'city' => 'Marbella',
                'bedrooms' => 3, 'bathrooms' => 2, 'property_size' => 210, 'land_size' => 450,
                'ownership_type' => 'freehold',
                'amenities' => ['Pool', 'Garden', 'Air Conditioning'],
                'is_verified' => false, 'status' => 'rejected', 'admin_note' => 'Please provide proof of ownership documents.',
            ],
            [
                'title' => 'Lakeside Corporate Offices',
                'category' => 'Commercial', 'listing_type' => 'rent', 'monthly_rent' => 12500, 'security_deposit' => 25000,
                'country' => 'United Kingdom', 'state' => 'England', 'city' => 'London',
                'property_size' => 760, 'parking' => '10 spaces', 'ownership_type' => 'leasehold',
                'amenities' => ['Elevator', 'Security', 'Backup Power'],
                'is_verified' => true, 'status' => 'published',
            ],
        ];

        foreach ($sample as $i => $item) {
            $existing = Property::where('title', $item['title'])->first();
            if ($existing) {
                continue;
            }

            $property = new Property();
            $property->fill([
                'user_id' => $i % 2 === 0 ? $owner->id : $rep->id,
                'title' => $item['title'],
                'location' => implode(', ', array_filter([$item['city'] ?? null, $item['country']])),
                'category' => $item['category'],
                'listing_type' => $item['listing_type'],
                'image_url' => $hero,
                'description' => 'A beautifully presented ' . strtolower($item['category']) . ' property with premium finishes, modern amenities and an excellent location. Perfect for families, investors or professionals. All viewings are arranged and supervised by Aurevia Property Support.',
                'country' => $item['country'], 'state' => $item['state'] ?? null, 'city' => $item['city'] ?? null,
                'bedrooms' => $item['bedrooms'] ?? null, 'bathrooms' => $item['bathrooms'] ?? null,
                'property_size' => $item['property_size'] ?? null, 'land_size' => $item['land_size'] ?? null,
                'parking' => $item['parking'] ?? null,
                'ownership_type' => $item['ownership_type'] ?? null,
                'price' => $item['price'] ?? null,
                'monthly_rent' => $item['monthly_rent'] ?? null,
                'security_deposit' => $item['security_deposit'] ?? null,
                'amenities_json' => $item['amenities'] ?? [],
                'is_verified' => $item['is_verified'] ?? false,
                'representative_role' => $i % 2 === 0 ? 'owner' : 'agent',
                'representative_verified' => true,
                'status' => $item['status'],
                'listed_at' => now()->subDays($i * 2),
            ]);
            $property->save();
            $property->listing_number = 'AVP-' . str_pad((string) $property->id, 5, '0', STR_PAD_LEFT);
            $property->appendLog('Seeded for demo.');
            $property->save();
        }

        $published = Property::where('status', 'published')->first();
        if ($published && PropertyInquiry::count() === 0) {
            $buyer = User::firstOrCreate(
                ['email' => 'demo.buyer@aurevia.test'],
                ['name' => 'James Carter', 'password' => Hash::make('password'), 'role' => 'user', 'kyc_status' => 'approved', 'kyc_verified' => true]
            );

            $viewing = PropertyInquiry::create([
                'property_id' => $published->id,
                'user_id' => $buyer->id,
                'type' => 'viewing',
                'full_name' => 'James Carter',
                'email' => 'demo.buyer@aurevia.test',
                'phone' => '+1 555 010 2345',
                'preferred_date' => now()->addDays(3)->toDateString(),
                'preferred_time' => '14:30',
                'viewing_type' => 'physical',
                'attendees' => 2,
                'message' => 'I would like to see the property this week if possible.',
                'preferred_channel' => 'whatsapp',
                'status' => 'awaiting_admin_review',
            ]);
            $viewing->appendLog('Viewing Request submitted, awaiting admin review.');
            $viewing->save();

            $interest = PropertyInquiry::create([
                'property_id' => $published->id,
                'user_id' => $buyer->id,
                'type' => 'purchase',
                'full_name' => 'James Carter',
                'email' => 'demo.buyer@aurevia.test',
                'phone' => '+1 555 010 2345',
                'message' => 'Interested in making an offer.',
                'preferred_channel' => 'telegram',
                'status' => 'representative_verification',
            ]);
            $interest->appendLog('Purchase Interest submitted.');
            $interest->save();

            PropertyConversation::create([
                'inquiry_id' => $interest->id,
                'property_id' => $published->id,
                'channel' => 'telegram_group',
                'external_link' => 'https://t.me/' . $published->ref() . '-deal',
                'participants' => ['James Carter (Buyer)', 'Sarah Mitchell (Agent)', 'Aurevia Property Support'],
                'status' => 'active',
            ]);

            PropertyReport::create([
                'property_id' => $published->id,
                'reporter_id' => $buyer->id,
                'report_type' => 'listing',
                'reason' => 'Test report: photos look different from the actual property.',
            ]);
        }

        \App\Models\Setting::set('whatsapp_handle', '15550101234');
        \App\Models\Setting::set('telegram_handle', 'aurevia_property_support');

        $this->command?->info('Properties marketplace demo data seeded.');
    }
}
