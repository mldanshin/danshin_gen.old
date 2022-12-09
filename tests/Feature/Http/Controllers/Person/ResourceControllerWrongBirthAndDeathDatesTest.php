<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\Dates as DatesDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongBirthAndDeathDatesTest extends TestCase
{
    use DatesDataProvider;
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;
    use UserDataProvider;

    public function test(): void
    {
        $this->seed();

        $gender = GenderEloquentModel::value("id");
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender,
                    "person_birth_date" => $this->faker->randomElement($this->getDeathDate()),
                    "person_death_date" => $this->faker->randomElement($this->getBirthDate())
                ]);
            $response->assertStatus(302);
        }
    }
}
