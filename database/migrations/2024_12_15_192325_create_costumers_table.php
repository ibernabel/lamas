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
        Schema::create('costumers', function (Blueprint $table) {
            $table->id();
            $table->string('NID')
                ->unique()
                ->comment('National Identification Number (NID)');
            $table->string('lead_channel')
                  ->comment('Channel used by the costumer to reach us');
            $table->boolean('is_referred')
                ->default(false)
                ->comment('Indicates if the costumer is referred');
            $table->string('referred_by')
                ->nullable()
                ->comment('NID of the costumer who referred this costumer');
            $table->boolean('is_active')
                ->default(true)
                ->comment('Indicates if the costumer is active');
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costumers');
    }
};
