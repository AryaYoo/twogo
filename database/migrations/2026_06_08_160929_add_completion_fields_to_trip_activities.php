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
            $table->string('photo')->nullable()->after('is_completed');
            $table->decimal('actual_cost', 15, 2)->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_activities', function (Blueprint $table) {
            $table->dropColumn(['photo', 'actual_cost']);
        });
    }
};
