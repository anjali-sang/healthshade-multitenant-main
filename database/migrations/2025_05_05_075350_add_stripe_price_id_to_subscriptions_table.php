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
        if (!Schema::hasColumn('subscriptions', 'stripe_price_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('stripe_price_id')->after('duration');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('subscriptions', 'stripe_price_id')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('stripe_price_id');
            });
        }
    }
};
