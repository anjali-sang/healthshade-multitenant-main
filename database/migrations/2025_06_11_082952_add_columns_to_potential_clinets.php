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
        Schema::table('potential_clients', function (Blueprint $table) {
            $table->string('otp', 6)->nullable()->after('email');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');
            $table->boolean('otp_verified')->default(false)->after('otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potential_clients', function (Blueprint $table) {
            $table->dropColumn([
                'otp',
                'otp_expires_at',
                'otp_verified'
            ]);
        });
    }
};
