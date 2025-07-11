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
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable(); 
            $table->string('location_id')->nullable(); ; 
            $table->string('on_hand_quantity')->default(0);
            $table->string('organization_id')->nullable();  
            $table->string('alert_quantity')->default(3); 
            $table->string('par_quantity')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_counts');
    }
};
