<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('price_per_share', 15, 2)->nullable()->change();
            $table->integer('total_shares')->nullable()->change();
            $table->integer('available_shares')->nullable()->change();
            $table->decimal('roi_percentage', 5, 2)->nullable()->change();
            $table->integer('investment_duration_months')->default(12)->nullable()->change();

            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('listing_number')->nullable()->unique()->after('uuid');
            $table->string('listing_type')->default('sale')->after('category'); // sale, rent
            $table->decimal('monthly_rent', 15, 2)->nullable()->after('price');
            $table->decimal('security_deposit', 15, 2)->nullable()->after('monthly_rent');
            $table->string('country')->nullable()->after('location');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('address')->nullable()->after('city');
            $table->unsignedInteger('bedrooms')->nullable()->after('address');
            $table->unsignedInteger('bathrooms')->nullable()->after('bedrooms');
            $table->decimal('property_size', 12, 2)->nullable()->after('bathrooms');
            $table->decimal('land_size', 12, 2)->nullable()->after('property_size');
            $table->string('parking')->nullable()->after('land_size');
            $table->json('amenities_json')->nullable()->after('parking');
            $table->string('ownership_type')->nullable()->after('amenities_json'); // freehold, leasehold, strata
            $table->string('video_url')->nullable()->after('description');
            $table->boolean('is_verified')->default(false)->after('video_url');
            $table->boolean('is_featured')->default(false)->after('is_verified');
            $table->unsignedInteger('views_count')->default(0)->after('is_featured');
            $table->string('representative_role')->nullable()->after('views_count'); // owner, agent, developer, property_manager
            $table->boolean('representative_verified')->default(false)->after('representative_role');
            $table->text('admin_note')->nullable()->after('representative_verified');
            $table->json('logs')->nullable()->after('admin_note');
            $table->timestamp('listed_at')->nullable()->after('logs');
            $table->timestamp('expires_at')->nullable()->after('listed_at');
        });

        DB::table('properties')->where('status', 'active')->update(['status' => 'published']);
        DB::table('properties')->where('status', 'sold_out')->update(['status' => 'sold']);
        DB::table('properties')->where('status', 'upcoming')->update(['status' => 'published']);

        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('document_type')->default('verification'); // verification, floor_plan, brochure, agreement
            $table->string('file_path');
            $table->boolean('is_restricted')->default(true);
            $table->timestamps();
        });

        Schema::create('property_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_number')->unique();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // purchase, rental, viewing, general
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->string('viewing_type')->nullable(); // physical, virtual
            $table->unsignedInteger('attendees')->default(1);
            $table->text('message')->nullable();
            $table->string('preferred_channel')->default('whatsapp'); // whatsapp, telegram
            $table->string('status')->default('awaiting_admin_review');
            // awaiting_admin_review, representative_verification, viewing_scheduled,
            // purchase_discussion, rental_review, completed, cancelled
            $table->text('admin_note')->nullable();
            $table->json('logs')->nullable();
            $table->timestamps();
        });

        Schema::create('property_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('property_inquiries')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('channel'); // whatsapp_group, telegram_group, call, meeting
            $table->string('external_link')->nullable();
            $table->json('participants')->nullable();
            $table->string('status')->default('active'); // active, closed
            $table->timestamps();
        });

        Schema::create('property_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('report_type'); // listing, fraud
            $table->text('reason');
            $table->string('status')->default('open'); // open, resolved, dismissed
            $table->timestamps();
        });

        Schema::create('property_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('rep_type')->nullable()->after('role'); // owner, agent, developer, property_manager
            $table->string('rep_status')->default('unverified')->after('rep_type'); // unverified, pending, verified, rejected
            $table->timestamp('rep_verified_at')->nullable()->after('rep_status');
            $table->json('rep_documents')->nullable()->after('rep_verified_at');
        });

        if (DB::table('property_categories')->count() === 0) {
            $seeds = [
                ['name' => 'Residential', 'slug' => 'residential', 'icon' => 'bi-house', 'sort_order' => 1],
                ['name' => 'Apartments', 'slug' => 'apartments', 'icon' => 'bi-building', 'sort_order' => 2],
                ['name' => 'Luxury', 'slug' => 'luxury', 'icon' => 'bi-gem', 'sort_order' => 3],
                ['name' => 'Commercial', 'slug' => 'commercial', 'icon' => 'bi-shop', 'sort_order' => 4],
                ['name' => 'Beachfront', 'slug' => 'beachfront', 'icon' => 'bi-umbrella', 'sort_order' => 5],
                ['name' => 'Land', 'slug' => 'land', 'icon' => 'bi-bounding-box', 'sort_order' => 6],
            ];
            foreach ($seeds as $seed) {
                DB::table('property_categories')->insert($seed + ['created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rep_type', 'rep_status', 'rep_verified_at', 'rep_documents']);
        });
        Schema::dropIfExists('property_reports');
        Schema::dropIfExists('property_conversations');
        Schema::dropIfExists('property_inquiries');
        Schema::dropIfExists('property_documents');
        Schema::dropIfExists('property_categories');

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', 'listing_number', 'listing_type', 'monthly_rent', 'security_deposit',
                'country', 'state', 'city', 'address', 'bedrooms', 'bathrooms', 'property_size',
                'land_size', 'parking', 'amenities_json', 'ownership_type', 'video_url',
                'is_verified', 'is_featured', 'views_count', 'representative_role',
                'representative_verified', 'admin_note', 'logs', 'listed_at', 'expires_at',
            ]);
        });
    }
};
