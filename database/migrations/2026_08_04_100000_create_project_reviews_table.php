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
        Schema::create('project_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('review')->nullable();
            $table->string('reviewer_name')->nullable(); // For admin-authored reviews
            $table->boolean('is_admin')->default(false);
            $table->timestamps();

            // Each user can only review a project once (non-admin reviews)
            $table->unique(['user_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_reviews');
    }
};
