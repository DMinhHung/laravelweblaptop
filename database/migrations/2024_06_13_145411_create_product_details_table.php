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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->text('CPU')->nullable();
            $table->text('RAM')->nullable();
            $table->text('HARDWARE')->nullable();
            $table->text('CARD')->nullable();
            $table->text('MONITOR')->nullable();
            $table->text('PIN')->nullable();
            $table->text('WEIGHT')->nullable();
            $table->text('MATERRIAL')->nullable();
            $table->text('LENGHT')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
