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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_oder_number', 20)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('bill_to_location_id')->nullable();
            $table->unsignedBigInteger('ship_to_location_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->decimal('total', 10, 2);
            $table->string('status')->default('ordered');
            $table->string('invoice')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
