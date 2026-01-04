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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // empleado
            $table->enum('type', ['vacation', 'sick', 'maternity', 'other'])->default('vacation');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable(); // motivo/descripción
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // quien solicita
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // quien aprueba/rechaza
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable(); // motivo de rechazo
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['company_id', 'user_id']);
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
