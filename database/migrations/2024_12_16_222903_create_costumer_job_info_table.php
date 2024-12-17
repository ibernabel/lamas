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
        Schema::create('costumer_job_info', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('level');
            $table->date('start_date');
            $table->float('salary');
            $table->string('payment_type');
            $table->string('payment_frequency');
            $table->string('payment_bank');
            $table->string('payment_account_number');
            $table->string('schedule');
            $table->string('supervisor_name');

            $table->foreignId('costumer_id')
                  ->constrained('costumers')
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
        Schema::dropIfExists('costumer_job_info');
    }
};
