<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Property::create([
            'title' => 'Oceanview Residences',
            'location' => 'Manila, Philippines',
            'category' => 'Residential',
            'price_per_share' => 100.00,
            'total_shares' => 500,
            'available_shares' => 500,
            'roi_percentage' => 12.00,
            'investment_duration_months' => 12,
            'status' => 'published',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
