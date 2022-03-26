<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    //Le indicamos que un producto puede tener varias categorías, y que saque las categorías relacionadas con el producto
    public function categorias(){
        return $this->hasMany('App\Category', 'categoria_cod');
    }

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
