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
    Schema::create('recensioni', function (Blueprint $table) {
        $table->id();
        $table->string('nome'); // nome del cliente
        $table->text('contenuto'); // testo della recensione
        $table->unsignedTinyInteger('voto')->default(5); // voto da 1 a 5
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recensioni');
    }
};
