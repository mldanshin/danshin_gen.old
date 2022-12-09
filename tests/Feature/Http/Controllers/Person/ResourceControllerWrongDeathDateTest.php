<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\Dates;
use Tests\TestCase;

final class ResourceControllerWrongDeathDateTest extends TestCase
{
    use Dates;
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;

    public function testPattern(): void
    {
        $this->seed();

        for ($i = 0; $i < 10; $i++) {
            $this->testItem(
                "person_death_date",
                $this->faker->randomElement($this->getDatePatternWrong())
            );
        }
    }

    public function testFuture(): void
    {
        $this->seed();

        for ($i = 0; $i < 10; $i++) {
            $this->testItem(
                "person_death_date",
                $this->faker->randomElement($this->getDateFuture())
            );
        }
    }
}
