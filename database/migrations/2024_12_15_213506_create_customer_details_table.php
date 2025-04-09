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
        Schema::create('customer_details', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('nickname')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'other'])->nullable();
            $table->enum('education_level', ['primary','secondary','high_school', 'bachelor', 'postgraduate', 'master', 'doctorate', 'other'])->nullable();
            $table->string('nationality')->nullable();
            $table->enum('housing_type', ['owned', 'rented', 'mortgaged','other'])->nullable();
            $table->date('move_in_date')->nullable();
            //$table->enum('vehicle_type', ['owned', 'rented', 'financed', 'none', 'other'])->nullable();

            $table->foreignId('customer_id')
                ->constrained('customers')
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
        Schema::dropIfExists('customer_details');
    }
};
