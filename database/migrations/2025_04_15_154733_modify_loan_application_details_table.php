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
            // Modificar campos para que sean nullable y tengan valores por defecto
            $table->integer('amount')->default(0)->change();
            $table->integer('term')->default(0)->change();
            $table->integer('rate')->default(0)->change();
            $table->float('quota')->nullable()->change();
            $table->enum('frequency', ["daily", "weekly", "bi-weekly", "fortnightly", "monthly"])->default('monthly')->change();
            $table->string('purpose')->nullable()->change();
            $table->string('customer_comment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_application_details', function (Blueprint $table) {
            // Revertir cambios
            $table->integer('amount')->nullable(false)->default(null)->change();
            $table->integer('term')->nullable(false)->default(null)->change();
            $table->integer('rate')->nullable(false)->default(null)->change();
            $table->float('quota')->nullable(false)->change();
            $table->enum('frequency', ["daily", "weekly", "bi-weekly", "fortnightly", "monthly"])->nullable(false)->default(null)->change();
            $table->string('purpose')->nullable(false)->change();
            $table->string('customer_comment')->nullable(false)->change();
        });
    }
};