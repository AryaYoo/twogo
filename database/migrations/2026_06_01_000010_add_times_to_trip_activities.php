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
        Schema::table('trip_activities', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('session');
            $table->time('end_time')->nullable()->after('start_time');
            $table->boolean('notified_start')->default(false)->after('is_completed');
            $table->boolean('notified_end')->default(false)->after('notified_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_activities', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'notified_start', 'notified_end']);
        });
    }
};
