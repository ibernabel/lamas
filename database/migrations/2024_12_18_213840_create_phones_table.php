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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('country_area')->nullable()->default('');
            $table->string('number')->nullable();
            $table->string('extension')->nullable()->default('');
            $table->enum('type', ['mobile', 'home', 'work'])->nullable()->default('mobile');
            $table->morphs('phoneable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
