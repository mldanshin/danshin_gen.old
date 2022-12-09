<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerPatronymicTest extends TestCase
{
    use FormData;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider createSuccessProvider
     */
    public function testSuccess(?string $expectedBefore, ?string $expectedAfter): void
    {
        //preparation
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();
        $user = $this->getAdmim();

        $person = PeopleEloquentModel::get()->random();

        //testing
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->put(
                route("partials.person.update", $person->id),
                [
                    "person_id" => $person->id,
                    "person_gender" => $person->gender()->first()->id,
                    "person_patronymic" => $expectedBefore
                ]
            );
        $response->assertStatus(200);

        $person = PeopleEloquentModel::find($person->id);
        $this->assertEquals($expectedAfter, $person->patronymic);
    }

    public function createSuccessProvider(): array
    {
        return [
            ["", null],
            ["!", ""],
            [null, null],
            ["Ivanov", "Ivanov"],
        ];
    }
}
