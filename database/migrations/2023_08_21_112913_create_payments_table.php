<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_id');
                $table->unsignedBigInteger('item_id')->nullable();
                $table->timestamps();
                $table->foreign('item_id')
                    ->references('id')
                    ->on('shops')
                    ->onDelete('set null');
            });
        } catch (\Exception $e) {
            dd($e);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};