<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recensione extends Model
{
    protected $table = 'recensioni';
    protected $fillable = ['nome', 'contenuto', 'voto'];
}
