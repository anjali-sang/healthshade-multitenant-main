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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'approved_by', 'is_deleted']);
            $table->string('organization_id')->after('id');   
            $table->string('category_id')->after('organization_id');       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_approved')->default(0);
            $table->string('approved_by')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->string('organization_id');
            $table->string('category_id');
        });
    }
};
