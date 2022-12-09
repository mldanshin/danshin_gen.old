<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Internet;
use App\Models\Eloquent\People;
use Illuminate\Database\Eloquent\Factories\Factory;

final class InternetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Internet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "person_id" => $this->faker->randomElement(People::pluck("id")->all()),
            "url" => $this->faker->unique()->url(),
            "name" => $this->faker->text()
        ];
    }
}
