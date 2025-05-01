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
            // Enum actualizado con todos los valores finales
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_year')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('vehicle_plate_number')->nullable();
            $table->enum('vehicle_possession_type', [
                'owned',
                'rented',
                'financed',
                'shared',
                'leased',
                'borrowed',
                'none',
                'other'
            ])->nullable()->comment('Type of possession of the vehicle');
            $table->boolean('is_financed')->default(false)->comment('Indicates if the vehicle is financed');
            $table->boolean('is_owned')->default(false)->comment('Indicates if the vehicle is owned');
            $table->boolean('is_leased')->default(false)->comment('Indicates if the vehicle is leased');
            $table->boolean('is_rented')->default(false)->comment('Indicates if the vehicle is rented');
            $table->boolean('is_shared')->default(false)->comment('Indicates if the vehicle is shared');
            $table->enum('vehicle_type', [
                'Sedan',
                'SUV',
                'Truck',
                'Van',
                'Coupe',
                'bike',
                'motorcycle',
                'other'
            ])->nullable()->comment('Type of vehicle owned by the customer');
            $table->enum('vehicle_purpose', [
                'personal',
                'business',
                'commercial'
            ])->nullable()->comment('Purpose of the vehicle');
            $table->integer('vehicle_mileage')->nullable()->comment('Mileage of the vehicle');
            $table->decimal('vehicle_value', 10, 2)->nullable()->comment('Value of the vehicle');
            $table->enum('vehicle_condition', [
                'new',
                'used',
                'damaged'
            ])->nullable()->comment('Condition of the vehicle');
            $table->boolean('is_insured')->default(false)->comment('Indicates if the vehicle is insured');
            $table->string('insurance_company')->nullable()->comment('Insurance company of the vehicle');
            $table->boolean('has_gps')->default(false)->comment('Indicates if the vehicle has GPS');
            $table->string('gps_company')->nullable()->comment('GPS company of the vehicle');

            // Clave foránea correcta apuntando a la tabla customers
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
        // Corregido el nombre de la tabla en el método down
        Schema::dropIfExists('customer_vehicles');
    }
};
