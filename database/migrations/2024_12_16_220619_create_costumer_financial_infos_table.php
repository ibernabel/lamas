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
        Schema::create('costumer_financial_info', function (Blueprint $table) {
            $table->id();
            $table->float('other_incomes')->nullable();
            $table->float('total_incomes')->nullable();
            $table->float('discounts')->nullable();
            $table->enum('housing_type', ['rented', 'owned', 'financed', 'borrowed'])->nullable();
            $table->float('monthly_housing_payment')->nullable();
            $table->float('total_debts')->nullable();
            $table->float('loan_installments')->nullable();
            $table->float('household_expenses')->nullable();
            $table->float('labor_benefits')->nullable();
            $table->float('guarantee_assets')->nullable();

            $table->foreignId('costumer_id')
                  ->constrained('costumers')
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
        Schema::dropIfExists('costumer_financial_info');
    }
};
