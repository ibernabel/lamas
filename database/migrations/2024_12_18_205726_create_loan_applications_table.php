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
            $table->enum('status',['received', 'verified', 'assigned', 'analyzed', 'approved', 'rejected', 'archived']);
            $table->dateTime('changed_status_at')->nullable();
            $table->boolean('is_answered')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_new')->default(true);
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_active')->default(true);
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->foreignId('customer_id')->nullable()
                  ->constrained()
                  ->onUpdate('cascade')
                  ->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID of the user associated with the loan application');
            $table->foreign('user_id')->references('id')->on('users');
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
