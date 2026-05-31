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
        Schema::create('trip_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_day_id')->constrained('trip_days')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('session', ['pagi', 'siang', 'malam']);
            $table->string('location_name')->nullable();
            $table->string('location_url')->nullable();
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->enum('category', ['wisata', 'kuliner', 'transportasi', 'akomodasi', 'belanja', 'lainnya']);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_activities');
    }
};
