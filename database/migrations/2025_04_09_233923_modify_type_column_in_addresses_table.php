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
        // 🔸 Primero, eliminamos la columna 'type' para poder recrearla como enum
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // 🔸 Luego, la recreamos como enum + modificamos otras columnas
        Schema::table('addresses', function (Blueprint $table) {
            $table->enum('type', ['home', 'work', 'billing', 'shipping'])
                  ->nullable()
                  ->default('home');

            $table->string('street2')->nullable()->default('')->change();
            $table->string('postal_code')->nullable()->default('')->change();
            $table->string('city')->nullable()->default('')->change();
            $table->string('state')->nullable()->default('')->change();
            $table->string('country')->nullable()->default('')->change();;
            //$table->text('references')->nullable()->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertimos los cambios
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->string('type')->nullable()->default('home');

            $table->string('street2')->nullable()->default(null)->change();
            $table->string('postal_code')->nullable()->default(null)->change();
            $table->string('city')->nullable()->default('')->change();
            $table->string('state')->nullable()->default('')->change();
            $table->string('country')->nullable()->default('')->change();;
            //$table->text('references')->nullable()->default(null)->change();
        });
    }
};
