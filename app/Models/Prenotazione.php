<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prenotazione extends Model
{
     use HasFactory;
     protected $table = 'prenotazioni';

    protected $fillable = [
        'data_inizio', 'data_fine', 'nome', 'email', 'telefono',
        'numero_stanze', 'numero_persone', 'note', 'stato', 'confermata_da_id',
        'tipo_pagamento', 'prezzo_totale', 'payment_gateway_id'
    ];

    protected $casts = [
        'confermata_da_id' => 'integer',
        'numero_stanze' => 'integer',
        'numero_persone' => 'integer',
        'prezzo_totale' => 'integer',
    ];

    public function getCodicePrenotazioneAttribute()
    {
        return 'VIL-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
