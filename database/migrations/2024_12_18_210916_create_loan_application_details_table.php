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
        Schema::create('loan_application_details', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->integer('term');
            $table->integer('rate');
            $table->float('quota');
            $table->enum('frecuency',["daily", "weekly", "bi-weekly", "fortnightly", "monthly"]);
            $table->string('purpose');
            $table->string('customer_comment');

            $table->foreignId('loan_application_id')
                  ->constrained('loan_applications')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application_details');
    }
};
