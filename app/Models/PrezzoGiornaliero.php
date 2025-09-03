<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PrezzoGiornaliero extends Model
{
    use HasFactory;

    protected $table = 'prezzi_giornalieri';


    protected $fillable = [
        'data',
        'prezzo_1',
        'prezzo_2', 
        'prezzo_3',
        'prezzo_4',
        'prezzo_5',
        'prezzo_6',
        'note', 
        'is_closed',
    ];

    protected $casts = [
        'data' => 'date',
        'prezzo_1' => 'decimal:2',
        'prezzo_2' => 'decimal:2',
        'prezzo_3' => 'decimal:2',
        'prezzo_4' => 'decimal:2',
        'prezzo_5' => 'decimal:2',
        'prezzo_6' => 'decimal:2',
        'is_closed' => 'boolean'
    ];

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('data', [$start, $end]);
    }
}