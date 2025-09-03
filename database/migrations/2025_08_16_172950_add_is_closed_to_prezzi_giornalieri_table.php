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
        Schema::table('prezzi_giornalieri', function (Blueprint $table) {
            $table->text('note')->nullable()->after('prezzo_6');
            $table->boolean('is_closed')->default(false)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prezzi_giornalieri', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('is_closed');
        });
    }
};
