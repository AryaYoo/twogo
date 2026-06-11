<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('amount');
            $table->string('source_type'); // e.g., 'trip_created', 'activity_completed', 'trip_completed', 'trip_liked', 'trip_cloned', 'friend_added', 'partner_bonus'
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_logs');
    }
};
