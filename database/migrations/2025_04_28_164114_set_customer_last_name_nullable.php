<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_details', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_details', function (Blueprint $table) {
            $table->string('last_name')->nullable(false)->change();
        // Revert to non-nullable if needed
        // Note: This will fail if there are existing null values in the column
        });

        if (DB::table('customer_details')->whereNull('last_name')->exists()) {
            throw new \Exception('Cannot set last_name to non-nullable due to existing null values.');
        }
        // Ensure there are no null values before reverting
        // This is a safeguard; you may want to handle this differently in production
        // e.g., by filling null values with a default value or removing those records
        // or by using a different migration strategy.
        }
};
