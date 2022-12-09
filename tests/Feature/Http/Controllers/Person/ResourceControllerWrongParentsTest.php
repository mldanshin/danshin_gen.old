<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\ParentRole as ParentRoleEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongParentsTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;
    use UserDataProvider;

    public function testMissingRole(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        for ($i = 0; $i < 5; $i++) {
            $person = $people->random();

            $this->testItem(
                "person_parents",
                [
                    [
                        "person" => $person->id
                    ]
                ]
            );
        }
    }

    public function testMissingPerson(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        for ($i = 0; $i < 5; $i++) {
            $roles = $people->random()->gender()->first()->parents()->pluck("parent_id");

            $this->testItem(
                "person_parents",
                [
                    [
                        "role" => $roles->random()
                    ]
                ]
            );
        }
    }

    public function testDuplicatePerson(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        for ($i = 0; $i < 5; $i++) {
            $person = $people->random();
            $roles = $person->gender()->first()->parents()->get();

            $role1 = $roles->random();
            $role2 = $roles->diff([$role1])->random();

            $this->testItem(
                "person_parents",
                [
                    [
                        "person" => $person->id,
                        "role" => $role1->id
                    ],
                    [
                        "person" => $person->id,
                        "role" => $role2->id
                    ]
                ]
            );
        }
    }

    public function testInvalidPerson(): void
    {
        $this->seed();

        $roles = ParentRoleEloquentModel::pluck("id");
        for ($i = 0; $i < 5; $i++) {
            $this->testItem(
                "person_parents",
                [
                    [
                        "person" => "fake",
                        "role" => $roles->random()
                    ]
                ]
            );
        }
    }

    public function testInvalidRole(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        for ($i = 0; $i < 5; $i++) {
            $person = $people->random();
            $roles = $person->gender()->first()->parents()->pluck("parent_id");
            $i = 0;
            while ($i < 5) {
                $roleWrong = $this->faker->unique()->randomNumber();
                if (!$roles->contains($roleWrong)) {
                    $i++;
                    $this->testItem(
                        "person_parents",
                        [
                            [
                                "person" => $person->id,
                                "role" => $roleWrong
                            ]
                        ]
                    );
                }
            }
        }
    }

    public function testUnifiedPersonAndParent(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(20)->get();
        foreach ($people as $person) {
            $roleParent = $person->gender()->first()->parents()->pluck("parent_id")->random();

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => $person->id,
                    "person_gender" => $person->gender()->first()->id,
                    "person_parents" => [
                        [
                            "person" => $person->id,
                            "role" => $roleParent
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }
}
