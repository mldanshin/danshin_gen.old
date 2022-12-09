<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Activity;
use App\Models\Eloquent\People;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "person_id" => $this->faker->randomElement(People::pluck("id")->all()),
            "name" => $this->faker->unique()->text()
        ];
    }
}
