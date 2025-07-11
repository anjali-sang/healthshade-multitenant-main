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
        Schema::create('shipment_products', function (Blueprint $table) {
            $table->id();

            $table->string('shipment_id'); // Not unique
            $table->string('product_id');
            $table->string('batch_id')->nullable(); // nullable if batch is optional
            $table->integer('quantity');
            $table->string('shipment_unit_id');
            $table->decimal('net_unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();

            // Optional foreign keys
            // $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('batch_id')->references('id')->on('batches')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_products');
    }
};
