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
        Schema::create('attendance_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // Empleado que solicita
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Supervisor/Admin que revisa
            $table->enum('type', ['time', 'date', 'status', 'other'])->default('time');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('requested_changes'); // JSON con los cambios solicitados
            $table->text('reason')->nullable(); // Razón de la solicitud
            $table->text('review_notes')->nullable(); // Notas del revisor
            $table->timestamp('reviewed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index(['attendance_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_corrections');
    }
};
