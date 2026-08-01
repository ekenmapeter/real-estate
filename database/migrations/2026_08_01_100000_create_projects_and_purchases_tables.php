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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('location');
            $table->string('category')->default('Residential');
            $table->string('image_url')->nullable();
            $table->decimal('target_amount', 15, 2);
            $table->decimal('minimum_investment', 15, 2);
            $table->decimal('expected_return_percentage', 5, 2);
            $table->integer('investment_duration_months')->default(12);
            $table->text('description')->nullable();
            $table->string('document_path')->nullable();
            $table->string('status')->default('active'); // active, completed, closed
            $table->timestamps();
        });

        Schema::create('project_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('expected_roi_amount', 15, 2);
            $table->decimal('roi_earned', 15, 2)->default(0.00);
            $table->string('status')->default('active'); // active, completed
            $table->timestamps();
        });

        Schema::create('saved_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'project_id']);
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('completed'); // completed, refunded
            $table->timestamps();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::dropIfExists('purchases');
        Schema::dropIfExists('saved_projects');
        Schema::dropIfExists('project_investments');
        Schema::dropIfExists('projects');
    }
};
