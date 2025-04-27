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
            // Drop the incorrect foreign key constraint referencing customer_details
            // The name comes from the original error message
            $table->dropForeign('customer_vehicles_customer_detail_id_foreign');

            // Add the correct foreign key constraint referencing customers
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_vehicles', function (Blueprint $table) {
            // Drop the correct foreign key constraint (Laravel's default naming convention)
            $table->dropForeign(['customer_id']); // Drop by column name

            // Re-add the incorrect foreign key constraint to allow rollback
            // Note: This constraint is functionally incorrect but needed for rollback.
            $table->foreign('customer_id', 'customer_vehicles_customer_detail_id_foreign') // Explicitly name it
                  ->references('id')
                  ->on('customer_details') // Pointing back to the wrong table
                  ->onDelete('cascade');
            // Note: onUpdate('cascade') might not have been on the original incorrect constraint,
            // but we add it here for consistency if needed. Adjust if the original was different.
        });
    }
};
