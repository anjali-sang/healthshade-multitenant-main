<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medrep_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('medical_rep_sales')->onDelete('cascade');
            $table->string('tracking_number')->unique();
            $table->string('carrier')->default('UPS');
            $table->string('service_type')->nullable();
            $table->string('label_url')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->enum('status', ['pending', 'shipped', 'in_transit', 'delivered', 'failed'])->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medrep_shipments');
    }
};
