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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('sales_date')->default(now());
            $table->unsignedBigInteger('total_price'); // Ubah ke bigInteger
            $table->unsignedBigInteger('total_pay');   // Ubah ke bigInteger
            $table->unsignedBigInteger('total_return'); // Ubah ke bigInteger
            $table->integer('poin')->default(0);
            $table->integer('total_poin')->default(0);
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};