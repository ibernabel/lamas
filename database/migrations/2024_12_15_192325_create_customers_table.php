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
    Schema::create('customers', function (Blueprint $table) {
      $table->id();
      $table->string('NID')
        ->unique()
        ->comment('National Identification Number (NID)');
      $table->string('lead_channel')
        ->comment('Channel used by the customer to reach us');
      $table->boolean('is_referred')
        ->default(false)
        ->comment('Indicates if the customer is referred');
      $table->string('referred_by')
        ->nullable()
        ->comment('NID of the customer who referred this customer');
      $table->boolean('is_active')
        ->default(true)
        ->comment('Indicates if the customer is active');
      $table->foreignId('portfolio_id')
        ->nullable()
        ->constrained('portfolios')
        ->nullOnDelete()
        ->cascadeOnUpdate();
      $table->foreignId('promoter_id')
        ->nullable()
        ->constrained('promoters')
        ->nullOnDelete()
        ->cascadeOnUpdate();
      $table->boolean('is_assigned')->default(false);
      $table->dateTime('assigned_at')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customers');
  }
};
