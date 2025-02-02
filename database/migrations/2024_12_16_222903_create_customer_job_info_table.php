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
        Schema::create('customer_job_info', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('level');
            $table->date('start_date');
            $table->float('salary');
            $table->float('other_incomes')->nullable();
            $table->string('other_incomes_source')->nullable();
            $table->string('payment_type');
            $table->string('payment_frequency');
            $table->string('payment_bank');
            $table->string('payment_account_number');
            $table->string('schedule');
            $table->string('supervisor_name');
            $table->boolean('is_self_employed')->default(false);

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade')
                  ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_job_info');
    }
};
