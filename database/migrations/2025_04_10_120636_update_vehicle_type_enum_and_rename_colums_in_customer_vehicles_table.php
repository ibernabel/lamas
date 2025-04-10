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
        Schema::table('customer_vehicles', function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
            $table->renameColumn('customer_detail_id', 'customer_id');
        });

        Schema::table('customer_vehicles', function (Blueprint $table) {
            $table->enum('vehicle_type', [
                'owned', 'rented', 'financed', 'shared', 'leased','borrowed', 'none', 'other'
            ])->nullable()->comment('Type of vehicle owned by the customer');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_vehicles', function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
            $table->renameColumn('customer_id', 'customer_detail_id');
        });

        Schema::table('customer_vehicles', function (Blueprint $table) {
            $table->enum('vehicle_type', [
                'owned', 'rented', 'financed', 'leased', 'none', 'other'
            ])->nullable()->comment('Type of vehicle owned by the customer');
        });
    }
};
