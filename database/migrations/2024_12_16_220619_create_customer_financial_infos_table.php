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
        Schema::create('customer_financial_info', function (Blueprint $table) {
            $table->id();
            $table->float('total_incomes')->nullable();
            $table->float('discounts')->nullable();
            $table->float('monthly_housing_payment')->nullable();
            $table->float('total_debts')->nullable();
            $table->float('loan_installments')->nullable();
            $table->float('household_expenses')->nullable();
            $table->float('labor_benefits')->nullable();
            $table->float('guarantee_assets')->nullable();
            $table->enum('mode_of_transport', ['public_transportation', 'own_car', 'own_motorcycle', 'bicycle', 'other'])->nullable();

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_financial_info');
    }
};
