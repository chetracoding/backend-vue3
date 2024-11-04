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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade');
            $table->unsignedBigInteger('table_id');
            $table->foreign('table_id')
                ->references('id')
                ->on('tables')
                ->onDelete('cascade');
            $table->dateTime('datetime');
            $table->boolean('is_completed');
            $table->boolean('is_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
