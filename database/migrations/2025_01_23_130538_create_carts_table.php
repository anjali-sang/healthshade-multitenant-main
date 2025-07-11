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
        Schema::create('carts', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('location_id'); 
            $table->unsignedBigInteger('added_by'); 
            $table->integer('quantity'); 
            $table->decimal('price', 10, 2); 
            $table->string('unit_id'); 
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
