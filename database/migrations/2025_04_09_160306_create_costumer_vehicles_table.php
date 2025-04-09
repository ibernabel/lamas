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
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('vehicle_type', ['owned', 'rented', 'financed', 'leased', 'none', 'other'])->nullable()->comment('Type of vehicle owned by the customer');
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('vehicle_plate_number')->nullable();
            $table->boolean('is_financed')->default(false)->comment('Indicates if the vehicle is financed');
            $table->boolean('is_owned')->default(false)->comment('Indicates if the vehicle is owned');
            $table->boolean('is_leased')->default(false)->comment('Indicates if the vehicle is leased');
            $table->boolean('is_rented')->default(false)->comment('Indicates if the vehicle is rented');
            $table->boolean('is_shared')->default(false)->comment('Indicates if the vehicle is shared');

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
        Schema::dropIfExists('costumer_vehicles');
    }
};
