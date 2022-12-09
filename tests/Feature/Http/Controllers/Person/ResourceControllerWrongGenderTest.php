<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongGenderTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use UserDataProvider;

    public function testMissing(): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.store"), [
                "person_id" => 0
            ]);
        $response->assertStatus(302);
    }

    public function testInvalid(): void
    {
        $this->seed();

        $gender = GenderEloquentModel::pluck("id");
        $i = 0;
        while ($i < 10) {
            $genderWrong = $this->faker->unique()->randomNumber();
            if (!$gender->contains($genderWrong)) {
                $i++;
                $response = $this->actingAs($this->getAdmim())
                    ->withSession(['banned' => false])
                    ->post(route("partials.person.store"), [
                        "person_id" => 0,
                        "person_gender" => $genderWrong
                    ]);
                $response->assertStatus(302);
            }
        }
    }
}
