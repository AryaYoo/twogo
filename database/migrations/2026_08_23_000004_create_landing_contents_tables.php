<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Landing Settings (Key-Value for Hero, Marquee, CTA, Footer)
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 2. Landing Features
        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('✨');
            $table->string('bg_color')->default('#00D4AA');
            $table->string('text_color')->default('#1A1A2E');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Landing Showcases
        Schema::create('landing_showcases', function (Blueprint $table) {
            $table->id();
            $table->string('section_badge')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('bullet_points')->nullable(); // JSON or newline delimited
            $table->string('badge_color')->default('#4361EE');
            $table->string('mockup_type')->default('itinerary'); // 'itinerary' or 'budget'
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Landing Stats
        Schema::create('landing_stats', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('label');
            $table->string('bg_color')->default('#FFE156');
            $table->string('text_color')->default('#1A1A2E');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. Landing Testimonials
        Schema::create('landing_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('user_tier')->nullable();
            $table->text('quote');
            $table->string('avatar_emoji')->default('🌟');
            $table->string('bg_color')->default('#FFF3C4');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_testimonials');
        Schema::dropIfExists('landing_stats');
        Schema::dropIfExists('landing_showcases');
        Schema::dropIfExists('landing_features');
        Schema::dropIfExists('landing_settings');
    }
};
