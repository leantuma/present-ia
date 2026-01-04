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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('qr_login_enabled')->default(true)->after('is_active');
            $table->boolean('geolocation_required')->default(false)->after('qr_login_enabled');
            $table->decimal('geolocation_radius_meters', 8, 2)->default(100)->after('geolocation_required');
            $table->decimal('geolocation_latitude', 10, 8)->nullable()->after('geolocation_radius_meters');
            $table->decimal('geolocation_longitude', 11, 8)->nullable()->after('geolocation_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'qr_login_enabled',
                'geolocation_required',
                'geolocation_radius_meters',
                'geolocation_latitude',
                'geolocation_longitude',
            ]);
        });
    }
};
