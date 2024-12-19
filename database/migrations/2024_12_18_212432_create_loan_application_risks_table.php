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
        Schema::create('loan_application_risks', function (Blueprint $table) {
            $table->id();
            $table->string('risk');
            $table->string('description');
            $table->foreignId('loan_application_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->foreignId('risk_category_id')->nullable()
                  ->constrained('loan_application_risk_categories')
                  ->onDelete('set null')
                  ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application_risks');
    }
};
