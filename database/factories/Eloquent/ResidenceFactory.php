<?php

namespace Database\Factories\Eloquent;

use App\Models\Eloquent\Residence;
use App\Models\Eloquent\People;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ResidenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Residence::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = [
            "2009-07-13",
            null,
            "2008-12-13",
            "2007-09-10",
            "2006-06-12",
            null,
            null,
            "2002-??-??"
        ];
        
        return [
            "person_id" => $this->faker->randomElement(People::pluck("id")->all()),
            "name" => $this->faker->address(),
            "date_info" => $this->faker->randomElement($date)
        ];
    }
}
