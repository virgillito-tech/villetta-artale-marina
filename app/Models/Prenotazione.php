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
        'numero_stanze', 'numero_persone', 'note', 'stato'
    ];

    public function getCodicePrenotazioneAttribute()
    {
        return 'VIL-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
