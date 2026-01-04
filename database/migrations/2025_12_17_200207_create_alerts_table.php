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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null = company-wide alert
            $table->foreignId('attendance_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['late_pattern', 'abnormal_behavior', 'absence', 'geolocation_mismatch', 'other'])->default('other');
            $table->string('title');
            $table->text('message');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable(); // For storing additional alert data
            $table->timestamps();
            
            $table->index(['company_id', 'user_id', 'is_read']);
            $table->index(['type', 'severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
