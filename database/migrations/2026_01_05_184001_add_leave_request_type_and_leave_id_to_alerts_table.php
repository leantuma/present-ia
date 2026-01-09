<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->foreignId('leave_id')->nullable()->after('attendance_id')->constrained('leaves')->onDelete('cascade');
        });

        // Modify enum to add 'leave_request' type
        DB::statement("ALTER TABLE alerts MODIFY COLUMN type ENUM('late_pattern', 'abnormal_behavior', 'absence', 'geolocation_mismatch', 'leave_request', 'other') DEFAULT 'other'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropForeign(['leave_id']);
            $table->dropColumn('leave_id');
        });

        // Revert enum to original values
        DB::statement("ALTER TABLE alerts MODIFY COLUMN type ENUM('late_pattern', 'abnormal_behavior', 'absence', 'geolocation_mismatch', 'other') DEFAULT 'other'");
    }
};
