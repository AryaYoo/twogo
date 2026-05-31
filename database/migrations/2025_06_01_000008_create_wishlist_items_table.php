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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['wisata', 'kuliner', 'belanja', 'lainnya']);
            $table->string('location_name')->nullable();
            $table->string('location_url')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('estimated_cost', 15, 2)->default(0);
            $table->enum('priority', ['wajib', 'pengen', 'kalau_sempat'])->default('pengen');
            $table->boolean('is_added_to_itinerary')->default(false);
            $table->json('votes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
