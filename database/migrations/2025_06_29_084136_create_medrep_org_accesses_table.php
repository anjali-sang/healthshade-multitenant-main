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
        Schema::create('medrep_org_accesses', function (Blueprint $table) {
            $table->id();
            $table->string('medrep_id');
            $table->string('org_id');
            $table->boolean('request_sent')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medrep_org_accesses');
    }
};
