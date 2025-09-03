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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->string('codice_fiscale')->unique()->after('email');
            $table->string('nome')->after('codice_fiscale');
            $table->string('cognome')->after('nome');
            $table->date('data_nascita')->nullable()->after('cognome');
            $table->string('luogo_nascita')->nullable()->after('data_nascita');
            $table->string('telefono')->nullable()->after('luogo_nascita');
            $table->string('indirizzo_residenza')->nullable()->after('telefono');
            $table->string('nazionalita')->nullable()->after('indirizzo_residenza');
            $table->enum('sesso', ['M', 'F', 'Altro'])->nullable()->after('nazionalita');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
            'username', 'codice_fiscale', 'nome', 'cognome', 'data_nascita', 'luogo_nascita', 
            'telefono', 'indirizzo_residenza', 'nazionalita', 'sesso'
            ]);
        });
    }
};
