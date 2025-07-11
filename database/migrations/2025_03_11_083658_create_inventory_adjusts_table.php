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
        Schema::create('inventory_adjusts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number');
            $table->string('product_id');
            $table->string('previous_quantity');
            $table->string('new_quantity');
            $table->string('quantity');
            $table->string('unit_id');
            $table->string('supplier_id');
            $table->string('location_id');
            $table->string('organization_id');
            $table->string('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_adjusts');
    }
};
