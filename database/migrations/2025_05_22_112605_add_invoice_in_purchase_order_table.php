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
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->string('invoice_path')->nullable();
                $table->string('acknowledgment_path')->nullable();
                $table->timestamp('invoice_uploaded_at')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['invoice_path', 'acknowledgment_path', 'invoice_uploaded_at']);
        });
    }
};
