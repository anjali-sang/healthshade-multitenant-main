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
        Schema::create('medical_rep_sales_products', function (Blueprint $table) {
            $table->id();
            $table->string('sales_id'); // Foreign key to medical_rep_sales
            $table->string('product_id'); // Product identifier
            $table->string('unit_id'); // Unit code
            $table->integer('quantity'); // Quantity of the product sold
            $table->decimal('price', 10, 2); // Price of the product
            $table->decimal('total', 10, 2); // Total price for the product (quantity * price)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_rep_sales_products');
    }
};
