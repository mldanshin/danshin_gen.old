<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ResourceControllerWrongUnavailableTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;

    public function testInvalid(): void
    {
        $this->seed();

        for ($i = 0; $i < 10; $i++) {
            $this->testItem(
                "person_unavailable",
                $this->faker->randomElement(["off", "0", "no", "false", "blabla"])
            );
        }
    }
}
