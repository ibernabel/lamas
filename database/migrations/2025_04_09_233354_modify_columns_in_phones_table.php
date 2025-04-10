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
        Schema::table('phones', function (Blueprint $table) {
            $table->string('country_area')->nullable()->default('')->change();
            $table->string('extension')->nullable()->default('')->change();
            $table->dropColumn('type');
            $table->enum('type', ['mobile', 'home', 'work'])->nullable()->default('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->string('country_area')->nullable(false)->default(null)->change();
            $table->string('extension')->nullable()->default(null)->change();
            $table->dropColumn('type');
            $table->enum('type', ['mobile', 'home', 'work'])->nullable()->default('mobile');
        });
    }
};
