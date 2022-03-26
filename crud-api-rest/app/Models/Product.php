<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_producto',
        'descripcion_producto',
        'fotos_producto',
        'categoria_cod',
        'tarifa',
        'created_at',
        'updated_at',
    ];
}
