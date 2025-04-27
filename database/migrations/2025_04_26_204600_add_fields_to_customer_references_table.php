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
            $table->dropColumn('phone_number');
            $table->string('NID')->nullable()->default(null)->comment('National Identification Number (NID)')->after('name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_references', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->default(null)->after('name');
            $table->dropColumn('NID');
        });
    }
};
