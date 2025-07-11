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
        Schema::create('batch_pickings', function (Blueprint $table) {
            $table->id();
            $table->string('picking_number')->unique();
            $table->string('location_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->string('organization_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('picking_quantity')->nullable();
            $table->string('picking_unit')->nullable();
            $table->string('net_unit_price')->nullable(); 
            $table->string('total_amount')->nullable();
            $table->string('chart_number')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_pickings');
    }
};
