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
        Schema::create('promoters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('NID')->unique()
              ->comment('National Identification Number (NID)');
            $table->enum('bonus_type', ['percentage', 'fixed'])
                ->default('fixed')
                ->comment('percentage or fixed');

            $table->integer('bonus_value')
                ->default(0)
                ->comment('percentage or fixed value');
            $table->string('bank_account_number'); 
            $table->string('bank_name');
            $table->string('bank_account_name'); 
            $table->string('bank_account_type');

            $table->foreignId('user_id')
                ->constrained('users')
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
        Schema::dropIfExists('promoters');
    }
};
