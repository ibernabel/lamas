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
        Schema::table('companies', function (Blueprint $table) {

          $table->string('email')->nullable()->default(null)->change();
          $table->string('type')->nullable()->default(null)->change();
          $table->string('rnc')->nullable()->default(null)->change();
          $table->string('website')->nullable()->default(null)->change();
          $table->string('departmet')->nullable()->default(null)->change();
          $table->string('branch')->nullable()->default(null)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {

          $table->string('email')->unique()->default('')->change();
          $table->string('type')->default('')->change();
          $table->string('rnc')->nullable()->default('')->change();
          $table->string('website')->nullable()->default('')->change();
          $table->string('departmet')->default('')->change();
          $table->string('branch')->default('')->change();

        });
    }
};
