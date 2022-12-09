<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Photo;
use App\Models\Eloquent\People;
use Illuminate\Database\Eloquent\Factories\Factory;

final class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;

    private $order = 1;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "person_id" => $this->faker->randomElement(People::pluck("id")->all()),
            "file" => $this->faker->uuid() . ".png",
            "_date" => null,
            "_order" => $this->order++
        ];
    }
}
