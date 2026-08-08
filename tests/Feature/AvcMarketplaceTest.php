<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvcMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'new',
            'email' => 'kofiadjo09@gmail.com',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/avc-marketplace')->assertRedirect('/login');
    }

    public function test_marketplace_hub_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace')
            ->assertOk()
            ->assertSee('AVC Marketplace')
            ->assertSee('Available AVC')
            ->assertSee('AVC Locked in Escrow')
            ->assertSee('Active Deals')
            ->assertSee('Create Listing')
            ->assertSee('My Listings')
            ->assertSee('My Deals')
            ->assertSee('Browse Listings')
            ->assertSee('AVC-8K01Z036')
            ->assertSee('Sarah J.**')
            ->assertSee('Deal via Admin')
            ->assertSee('Buy This AVC')
            ->assertSee('How It Works')
            ->assertSee('Admin Escrow Team')
            ->assertSee('Marketplace Safety Notice')
            ->assertSee('Continue on Telegram')
            ->assertSee('Continue on WhatsApp');
    }

    public function test_own_listing_shows_your_listing_badge(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace')
            ->assertOk()
            ->assertSee('AVC-2D5F7H44')
            ->assertSee('Your Listing');
    }

    public function test_create_listing_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/create')
            ->assertOk()
            ->assertSee('Create Listing')
            ->assertSee('Sell AVC')
            ->assertSee('Buy AVC')
            ->assertSee('Amount of AVC')
            ->assertSee('Preferred Payment Method')
            ->assertSee('Do not include bank account details')
            ->assertSee('Review Your Listing')
            ->assertSee('Submit Listing')
            ->assertSee('Pending Review');
    }

    public function test_my_listings_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/my-listings')
            ->assertOk()
            ->assertSee('My Listings')
            ->assertSee('AVC-2D5F7H44')
            ->assertSee('Pending review')
            ->assertSee('Changes required');
    }

    public function test_my_deals_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/my-deals')
            ->assertOk()
            ->assertSee('My Deals')
            ->assertSee('AVCD-702913')
            ->assertSee('AVCD-640138')
            ->assertSee('Seller Confirmed')
            ->assertSee('Awaiting Payment');
    }

    public function test_deal_page_loads_for_buyer(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/deals/AVCD-702913')
            ->assertOk()
            ->assertSee('AVCD-702913')
            ->assertSee('Deal Progress')
            ->assertSee('Buyer payment pending')
            ->assertSee('Payment Deadline')
            ->assertSee('Payment Instructions')
            ->assertSee('I Have Made Payment')
            ->assertSee('Report a Problem')
            ->assertSee('AVC secured in escrow');
    }

    public function test_deal_page_loads_for_seller(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/deals/AVCD-640138')
            ->assertOk()
            ->assertSee('Confirm Payment and Authorize AVC Release')
            ->assertSee('Seller Confirmed')
            ->assertSee('Payment Not Received');
    }

    public function test_completed_deal_page_loads(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/deals/AVCD-532991')
            ->assertOk()
            ->assertSee('Deal Completed')
            ->assertSee('Download Receipt');
    }

    public function test_unknown_deal_returns_404(): void
    {
        $this->actingAs($this->user)
            ->get('/avc-marketplace/deals/AVCD-999999')
            ->assertNotFound();
    }

    public function test_admin_can_view_marketplace(): void
    {
        $admin = User::factory()->create([
            'name' => 'Finance Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/avc-marketplace')
            ->assertOk()
            ->assertSee('AVC Marketplace');
    }
}
