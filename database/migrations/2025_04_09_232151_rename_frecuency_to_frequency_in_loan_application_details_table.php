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
        Schema::table('loan_application_details', function (Blueprint $table) {
            $table->renameColumn('frecuency', 'frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_application_details', function (Blueprint $table) {
            $table->renameColumn('frequency', 'frecuency');
        });
    }
};
