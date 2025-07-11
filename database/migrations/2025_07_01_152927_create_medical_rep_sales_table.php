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
        Schema::create('medical_rep_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sales_number');
            $table->string('medical_rep_id');
            $table->string('org_id')->nullable();
            $table->string('receiver_org_id')->nullable();
            $table->string('location_id')->nullable();
            $table->string('items')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('total_price')->nullable();
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_rep_sales');
    }
};
