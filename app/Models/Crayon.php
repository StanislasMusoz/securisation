<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crayon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'quantite',
    ];
}
