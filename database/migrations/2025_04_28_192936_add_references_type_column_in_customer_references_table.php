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
        Schema::table('customer_references', function (Blueprint $table) {
            $table->enum('type', [
                'personal',
                'professional',
                'guarantor',
                'academic',
                'commercial',
                'credit',
                'banking',
                'tenant',
                'character',
                'technical',
                'client',
                'supplier',
                'community'
            ])->nullable()->default('personal')->after('customer_id')->comment('Type of reference: personal or professional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_references', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
