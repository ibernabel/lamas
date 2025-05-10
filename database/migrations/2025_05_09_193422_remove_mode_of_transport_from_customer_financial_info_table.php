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
        Schema::table('customer_financial_info', function (Blueprint $table) {
            $table->dropColumn('mode_of_transport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_financial_info', function (Blueprint $table) {
            $table->enum('mode_of_transport', ['public_transportation', 'own_car', 'own_motorcycle', 'bicycle', 'other'])->nullable();
        });
    }
};
