<?php

namespace Database\Factories\Model\Admin;

use App\Model\Admin\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'identity' => $this->faker->unique()->uuid,
            'pid' => 0,
            'model_id' => 1,
        ];
    }
}
