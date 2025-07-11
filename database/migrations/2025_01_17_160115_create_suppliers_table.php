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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->string('supplier_slug')->unique();
            $table->string('supplier_email');
            $table->string('supplier_phone')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_city')->nullable();
            $table->string('supplier_state')->nullable();
            $table->string('supplier_country')->nullable();
            $table->string('supplier_zip')->nullable();
            $table->string('supplier_vat')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
