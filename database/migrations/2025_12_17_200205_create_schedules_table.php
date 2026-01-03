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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null = company-wide
            $table->string('name');
            $table->enum('type', ['fixed', 'rotating'])->default('fixed');
            $table->time('start_time');
            $table->time('end_time');
            $table->json('days_of_week'); // [1,2,3,4,5] for Mon-Fri, or rotating pattern
            $table->integer('tolerance_minutes')->default(15); // Minutes allowed before marked late
            $table->boolean('requires_location')->default(false);
            $table->decimal('location_latitude', 10, 8)->nullable();
            $table->decimal('location_longitude', 11, 8)->nullable();
            $table->integer('location_radius_meters')->default(100); // Radius in meters
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['company_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
