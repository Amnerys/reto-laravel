<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //Este modelo usará la tabla 'categories'
    protected $table = 'categories';

    //Relación de muchos productos pueden tener muchas categorías n-n
    public function productos(){
        return $this->belongsToMany('App\Models\Product');
    }

    protected $fillable = [
        'nombre_categoria',
        'descripcion_categoria',
        'created_at',
        'updated_at',
    ];
}
