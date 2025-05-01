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
            $table->string('country_area')->nullable();
            $table->string('number');
            $table->string('extension')->nullable();
            $table->enum('type', ['mobile', 'home', 'work', 'fax', 'other'])->nullable()->default('mobile');
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
