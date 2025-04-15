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
        Schema::table('customer_job_info', function (Blueprint $table) {
            $table->string('level')->nullable()->change();
            $table->string('payment_type')->nullable()->change();
            $table->string('payment_frequency')->nullable()->change();
            $table->string('payment_bank')->nullable()->change();
            $table->string('payment_account_number')->nullable()->change();
            $table->string('schedule')->nullable()->change();
            $table->string('supervisor_name')->nullable()->change();
            $table->string('role')->default('employee')->change();
            $table->date('start_date')->default('1900-01-01')->change();
            $table->integer('salary')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_job_info', function (Blueprint $table) {
            $table->string('level')->nullable(false)->change();
            $table->string('payment_type')->nullable(false)->change();
            $table->string('payment_frequency')->nullable(false)->change();
            $table->string('payment_bank')->nullable(false)->change();
            $table->string('payment_account_number')->nullable(false)->change();
            $table->string('schedule')->nullable(false)->change();
            $table->string('supervisor_name')->nullable(false)->change();
            $table->string('role')->default(null)->change();
            $table->date('start_date')->default(null)->change();
            $table->integer('salary')->default(null)->change();
        });
    }
};
