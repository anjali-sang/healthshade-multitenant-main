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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_number')->unique();
            
            $table->string('user_id');
            $table->string('customer_id');
            $table->string('location_id');

            $table->integer('total_quantity');
            $table->decimal('total_price', 10, 2);
            $table->decimal('grand_total', 10, 2);

            $table->timestamps();

            // Optional: Add foreign keys if applicable
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
