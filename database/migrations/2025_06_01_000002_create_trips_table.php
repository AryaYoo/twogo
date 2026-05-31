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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination');
            $table->string('cover_image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_budget', 15, 2)->default(0);
            $table->string('invite_code', 8)->unique();
            $table->enum('status', ['planning', 'ongoing', 'completed'])->default('planning');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
