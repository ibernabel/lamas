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
    Schema::table('customer_references', function (Blueprint $table) {

      $table->renameColumn('ocupation', 'occupation');
      $table->string('name')->nullable()->default(null)->change();
      $table->string('phone_number')->nullable()->default(null)->change();
      $table->string('relationship')->nullable()->default(null)->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('customer_references', function (Blueprint $table) {
      $table->renameColumn('occupation', 'ocupation');
      $table->string('name')->nullable()->default('')->change();
      $table->string('phone_number')->nullable()->default('')->change();
      $table->string('relationship')->nullable()->default('')->change();
    });
  }
};
