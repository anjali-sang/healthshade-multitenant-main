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
        Schema::create('picking_details', function (Blueprint $table) {
            $table->id();
            $table->string( 'picking_id');
            $table->string( 'product_id');
            $table->string('picking_quantity');
            $table->string('picking_unit');
            $table->integer('net_unit_price');
            $table->integer('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picking_details_models');
    }
};
