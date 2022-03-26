<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //Uso la librería faker junto con realText para crear textos casi legibles
            'nombre_categoria' => $this->faker->realText(30),
            'descripcion_categoria' => $this->faker->realText(250),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
