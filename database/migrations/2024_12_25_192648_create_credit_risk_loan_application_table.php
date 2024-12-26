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
        Schema::create('credit_risk_loan_application', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreignId('credit_risk_id')
                  ->constrained('credit_risks')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_risk_loan_application');
    }
};
