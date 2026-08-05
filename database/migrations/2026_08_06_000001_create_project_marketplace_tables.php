<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add extra property specs & funding fields to projects table
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'property_type')) {
                $table->string('property_type')->default('Multi-Family Residential')->after('category');
            }
            if (!Schema::hasColumn('projects', 'bedrooms')) {
                $table->string('bedrooms')->nullable()->after('property_type');
            }
            if (!Schema::hasColumn('projects', 'bathrooms')) {
                $table->string('bathrooms')->nullable()->after('bedrooms');
            }
            if (!Schema::hasColumn('projects', 'land_size_sqm')) {
                $table->string('land_size_sqm')->nullable()->after('bathrooms');
            }
            if (!Schema::hasColumn('projects', 'building_size_sqm')) {
                $table->string('building_size_sqm')->nullable()->after('land_size_sqm');
            }
            if (!Schema::hasColumn('projects', 'parking_spaces')) {
                $table->string('parking_spaces')->nullable()->after('building_size_sqm');
            }
            if (!Schema::hasColumn('projects', 'floors')) {
                $table->string('floors')->nullable()->after('parking_spaces');
            }
            if (!Schema::hasColumn('projects', 'total_units')) {
                $table->string('total_units')->nullable()->after('floors');
            }
            if (!Schema::hasColumn('projects', 'amenities_json')) {
                $table->json('amenities_json')->nullable()->after('total_units');
            }
            if (!Schema::hasColumn('projects', 'developer_summary')) {
                $table->text('developer_summary')->nullable()->after('description');
            }
            if (!Schema::hasColumn('projects', 'purpose')) {
                $table->text('purpose')->nullable()->after('developer_summary');
            }
            if (!Schema::hasColumn('projects', 'revenue_source')) {
                $table->text('revenue_source')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('projects', 'current_stage')) {
                $table->string('current_stage')->default('Construction')->after('revenue_source');
            }
            if (!Schema::hasColumn('projects', 'year_built')) {
                $table->string('year_built')->nullable()->after('current_stage');
            }
            if (!Schema::hasColumn('projects', 'condition')) {
                $table->string('condition')->default('New Development')->after('year_built');
            }
            if (!Schema::hasColumn('projects', 'share_price')) {
                $table->decimal('share_price', 15, 2)->default(100.00)->after('target_amount');
            }
            if (!Schema::hasColumn('projects', 'funding_closing_date')) {
                $table->timestamp('funding_closing_date')->nullable()->after('investment_duration_months');
            }
            if (!Schema::hasColumn('projects', 'is_verified')) {
                $table->boolean('is_verified')->default(true)->after('status');
            }
        });

        // 2. Project Duration Tiers Table (14 Days, 1 Month, 3 Months per project)
        if (!Schema::hasTable('project_duration_tiers')) {
            Schema::create('project_duration_tiers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->string('duration_key'); // '14_days', '1_month', '3_months'
                $table->string('duration_label'); // '14 Days', '1 Month', '3 Months'
                $table->integer('duration_days'); // 14, 30, 90
                $table->integer('required_shares'); // 10, 25, 50
                $table->decimal('min_avc_value', 15, 2); // 1000, 2500, 5000
                $table->decimal('target_earnings_pct', 5, 2); // 4.00, 8.00, 16.00
                $table->boolean('is_popular')->default(false);
                $table->timestamps();
            });
        }

        // 3. User Project Share Cycles Table
        if (!Schema::hasTable('project_share_cycles')) {
            Schema::create('project_share_cycles', function (Blueprint $table) {
                $table->id();
                $table->string('cycle_code')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->string('duration_key'); // '14_days', '1_month', '3_months'
                $table->string('duration_label'); // '14 Days', '1 Month', '3 Months'
                $table->integer('duration_days');
                $table->integer('shares_owned')->default(0);
                $table->integer('required_shares');
                $table->decimal('share_price', 15, 2);
                $table->decimal('total_purchase_amount', 15, 2);
                $table->decimal('target_earnings_pct', 5, 2);
                $table->decimal('projected_earnings', 15, 2);
                $table->decimal('completion_value', 15, 2);
                $table->string('status')->default('pending_activation'); // 'pending_activation', 'active', 'completed', 'refunded'
                $table->timestamp('purchased_at')->useCurrent();
                $table->timestamp('activated_at')->nullable();
                $table->timestamp('completion_date')->nullable();
                $table->timestamp('earnings_credited_at')->nullable();
                $table->string('receipt_number')->unique();
                $table->timestamps();
            });
        }

        // 4. Project Documents Table
        if (!Schema::hasTable('project_documents')) {
            Schema::create('project_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->string('document_type')->default('brochure'); // brochure, plans, valuation, terms, agreement, updates
                $table->string('file_path');
                $table->boolean('is_restricted')->default(false); // require KYC
                $table->timestamps();
            });
        }

        // 5. Project Updates Table
        if (!Schema::hasTable('project_updates')) {
            Schema::create('project_updates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->string('category')->default('Construction Progress'); // Construction Progress, Funding Milestones, Announcements
                $table->text('content');
                $table->string('image_url')->nullable();
                $table->timestamp('published_at')->useCurrent();
                $table->timestamps();
            });
        }

        // 6. Add security PIN to users table if missing
        if (!Schema::hasColumn('users', 'security_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('security_pin')->nullable()->after('password');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_share_cycles');
        Schema::dropIfExists('project_duration_tiers');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'property_type', 'bedrooms', 'bathrooms', 'land_size_sqm', 'building_size_sqm',
                'parking_spaces', 'floors', 'total_units', 'amenities_json', 'developer_summary',
                'purpose', 'revenue_source', 'current_stage', 'year_built', 'condition',
                'share_price', 'funding_closing_date', 'is_verified'
            ]);
        });
    }
};
