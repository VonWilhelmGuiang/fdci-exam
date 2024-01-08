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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id('contact_id');
            $table->foreignId('account_id')->references('account_id')->on('accounts')->constrained();
            $table->string('name',100);
            $table->string('company',100)->nullable();
            $table->string('phone',50)->nullable();
            //$table->string('email',50)->unique();
            $table->string('email',50)->nullable();
            $table->tinyInteger('active')->default(1)->comment('0-deleted, 1-active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
