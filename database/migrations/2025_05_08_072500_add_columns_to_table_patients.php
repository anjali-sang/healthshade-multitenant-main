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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('ins_type')->nullable();
            $table->string('provider')->nullable();
            $table->string('icd')->nullable();
            $table->string('account_number')->nullable();
            $table->string('drug')->nullable();
            $table->string('dose')->nullable();
            $table->string('frequency')->nullable();
            $table->string('location')->nullable();
            $table->string('pa_expires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('ins_type');
            $table->dropColumn('provider');
            $table->dropColumn('icd');
            $table->dropColumn('account_number');
            $table->dropColumn('drug');
            $table->dropColumn('dose');
            $table->dropColumn('frequency');
            $table->dropColumn('location');
            $table->dropColumn('pa_expires');
        });
    }
};
