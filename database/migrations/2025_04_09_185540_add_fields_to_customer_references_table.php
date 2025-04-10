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

            $table->string('reference_email')->nullable()->after('phone_number')->comment('Email address of the reference');
            $table->date('reference_since')->nullable()->after('relationship')->comment('Date since the customer has known the reference');
            $table->boolean('is_active')->default(true)->after('reference_since')->comment('Indicates if the reference is active');
            $table->string('ocupation')->nullable()->after('is_active')->comment('Occupation of the reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_references', function (Blueprint $table) {
            $table->dropColumn('reference_email');
            $table->dropColumn('reference_since');
            $table->dropColumn('is_active');
        });
    }
};
