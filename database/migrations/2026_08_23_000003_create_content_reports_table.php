<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason');
            $table->text('details')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'resolved', 'dismissed'
            $table->timestamps();
        });

        Schema::table('trips', function (Blueprint $table) {
            if (!Schema::hasColumn('trips', 'is_flagged')) {
                $table->boolean('is_flagged')->default(false)->after('is_public');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_reports');
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('is_flagged');
        });
    }
};
