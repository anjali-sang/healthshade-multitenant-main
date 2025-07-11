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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('product_name');
            $table->string('product_code');
            $table->string('product_supplier_id');
            $table->string('product_description')->nullable();
            $table->string('product_price');
            $table->string('manufacture_code')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->string('created_by');
            $table->string( 'updated_by');
            $table->boolean('is_approved')->default(true);
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
