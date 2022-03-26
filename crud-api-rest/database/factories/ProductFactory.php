<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //Uso la librería faker para generar datos falsos
            'nombre_producto' => $this->faker->realText(30),
            'descripcion_producto' => $this->faker->realText(250),
            'foto' => 'foto.jpg',
            'categoria_cod' => rand(1,10), //Solo hemos creado 10 categorías
            'tarifa' => $this->faker->randomFloat(2, 0, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
