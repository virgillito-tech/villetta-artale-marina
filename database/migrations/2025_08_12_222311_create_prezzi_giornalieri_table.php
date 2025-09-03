<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prezzi_giornalieri', function (Blueprint $table) {
            $table->id();
            $table->date('data')->unique();
            $table->decimal('prezzo_1', 8, 2)->nullable();
            $table->decimal('prezzo_2', 8, 2)->nullable();
            $table->decimal('prezzo_3', 8, 2)->nullable();
            $table->decimal('prezzo_4', 8, 2)->nullable();
            $table->decimal('prezzo_5', 8, 2)->nullable();
            $table->decimal('prezzo_6', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('prezzi_giornalieri');
    }
};
