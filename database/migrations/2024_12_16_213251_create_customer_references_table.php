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
        Schema::create('customer_references', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Made nullable
            $table->string('NID')->nullable()->comment('National Identification Number (NID)'); // Added NID, removed phone_number
            $table->string('relationship')->nullable(); // Made nullable
            $table->string('email')->nullable()->comment('Email address of the reference'); // Added
            $table->date('reference_since')->nullable()->comment('Date since the customer has known the reference'); // Added
            $table->string('occupation')->nullable()->comment('Occupation of the reference'); // Added (final name)
            $table->boolean('is_active')->default(true)->comment('Indicates if the reference is active'); // Added
            $table->boolean('is_who_referred')->default(false); // Kept from initial

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate(); // Moved cascadeOnUpdate here and added semicolon
            // Added type column
            $table->enum('type', [
                'personal', 'professional', 'guarantor', 'academic', 'commercial',
                'credit', 'banking', 'tenant', 'character', 'technical',
                'client', 'supplier', 'community', 'other'
            ])->nullable()->default('personal')->comment('Type of reference: personal or professional');
            // Removed misplaced ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_references');
    }
};
