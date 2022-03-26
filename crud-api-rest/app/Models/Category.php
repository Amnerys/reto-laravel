<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //Este modelo usará la tabla 'categories'
    protected $table = 'categories';

    //Le indicamos que una categoría puede tener varios productos y que saque los productos relacionados con la categoría
    public function productos(){
        return $this->hasMany('App\Product');
    }

    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
        'created_at',
        'updated_at',
    ];
}
