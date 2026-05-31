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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('paid_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->enum('category', ['akomodasi', 'transportasi', 'kuliner', 'tiket', 'belanja', 'lainnya']);
            $table->enum('split_type', ['equal', 'custom', 'solo'])->default('equal');
            $table->string('receipt_image')->nullable();
            $table->text('notes')->nullable();
            $table->date('expense_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
