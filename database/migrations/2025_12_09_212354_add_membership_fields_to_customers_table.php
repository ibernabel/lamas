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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('membership_status', 20)->default('INACTIVE');
            $table->dateTime('membership_approved_date')->nullable();
            $table->boolean('has_bankbook')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['membership_status', 'membership_approved_date', 'has_bankbook']);
        });
    }
};
