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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->enum('status',["recibida", "verificada", "asignada", "analizada", "aprobada", "rechazada", "archivada"]);
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_new')->default(true);
            $table->foreignId('costumer_id')->nullable()
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
