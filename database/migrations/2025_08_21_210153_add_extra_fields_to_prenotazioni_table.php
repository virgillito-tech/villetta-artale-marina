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
        Schema::table('prenotazioni', function (Blueprint $table) {
            $table->string('telefono')->nullable()->after('email');
            $table->integer('numero_persone')->default(1)->after('data_fine');
            $table->integer('numero_stanze')->default(1)->after('numero_persone');
            $table->text('note')->nullable()->after('numero_persone');
            $table->string('stato')->default('in attesa')->after('note');
            $table->decimal('prezzo_totale', 8, 2)->nullable()->after('stato');
            $table->string('tipo_pagamento')->nullable()->after('prezzo_totale');
            $table->unsignedBigInteger('confermata_da_id')->nullable()->after('stato');
            $table->foreign('confermata_da_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prenotazioni', function (Blueprint $table) {
            $table->dropColumn([
                'telefono',
                'numero_stanze',
                'numero_persone',
                'note',
                'stato',
                'prezzo_totale',
                'tipo_pagamento',
                'confermata_da_id',
            ]);
        });
    }
};
