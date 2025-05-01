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
            $table->integer('amount')->nullable(); // Added default
            $table->integer('term')->nullable(); // Added default
            $table->integer('rate')->nullable(); // Added default
            $table->float('quota')->nullable(); // Added nullable
            $table->enum('frequency',["daily", "weekly", "bi-weekly", "fortnightly", "monthly"])->default("monthly"); // Renamed column and added default
            $table->string('purpose')->nullable(); // Added nullable
            $table->string('customer_comment')->nullable(); // Added nullable

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
