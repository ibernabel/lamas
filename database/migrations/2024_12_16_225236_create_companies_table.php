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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('rnc')->unique()->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->string('departmet')->nullable()->default(null);
            $table->string('branch')->nullable()->default(null);

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
