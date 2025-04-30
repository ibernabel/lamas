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
            $table->string('name')->nullable(); // Made nullable
            $table->string('NID')->nullable()->comment('National Identification Number (NID)')->after('name'); // Added NID, removed phone_number
            $table->string('relationship')->nullable(); // Made nullable
            $table->string('email')->nullable()->after('relationship')->comment('Email address of the reference'); // Added
            $table->date('reference_since')->nullable()->after('email')->comment('Date since the customer has known the reference'); // Added
            $table->string('occupation')->nullable()->after('reference_since')->comment('Occupation of the reference'); // Added (final name)
            $table->boolean('is_active')->default(true)->after('occupation')->comment('Indicates if the reference is active'); // Added
            $table->boolean('is_who_referred')->default(false); // Kept from initial

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate(); // Moved cascadeOnUpdate here and added semicolon
            // Added type column
            $table->enum('type', [
                'personal', 'professional', 'guarantor', 'academic', 'commercial',
                'credit', 'banking', 'tenant', 'character', 'technical',
                'client', 'supplier', 'community'
            ])->nullable()->default('personal')->after('customer_id')->comment('Type of reference: personal or professional');
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
