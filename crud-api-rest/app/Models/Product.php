<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    //Relación de un producto puede tener muchas categorías (y a la inversa), encontrar en la tabla products por el id de category_id
    public function categorias(){
        return $this->belongsToMany('App\Models\Category', 'products', 'id');
    }

    protected $fillable = [
        'nombre_producto',
        'descripcion_producto',
        'foto',
        'category_id',
        'tarifa',
        'created_at',
        'updated_at',
    ];
}
