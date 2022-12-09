<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\Dates as DatesDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongMarriagesTest extends TestCase
{
    use DatesDataProvider;
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;
    use UserDataProvider;

    public function testMissingRoleCurrent(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 15; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $rolesCurrent->random(),
                            "soulmate" => $personSoulmate->id
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }

    public function testMissingSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 15; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");
            $rolesSoulmate = $personSoulmate->gender()->first()->marriages()->pluck("role_id");


            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $rolesCurrent->random(),
                            "role_soulmate" => $rolesSoulmate->random()
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }

    public function testMissingRoleSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 15; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $rolesCurrent->random(),
                            "soulmate" => $personSoulmate->id
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }

    public function testDuplicateSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 15; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");
            $rolesSoulmate = $personSoulmate->gender()->first()->marriages()->pluck("role_id");

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $rolesCurrent->random(),
                            "soulmate" => $personSoulmate->id,
                            "role_soulmate" => $rolesSoulmate->random()
                        ],
                        [
                            "role_current" => $rolesCurrent->random(),
                            "soulmate" => $personSoulmate->id,
                            "role_soulmate" => $rolesSoulmate->random()
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }

    public function testInvalidRoleCurrent(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 5; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");
            $rolesSoulmate = $personSoulmate->gender()->first()->marriages()->pluck("role_id");

            $i = 0;
            while ($i < 5) {
                $roleWrong = $this->faker->unique()->randomNumber();
                if (!$rolesCurrent->contains($roleWrong)) {
                    $i++;
                    $response = $this->actingAs($this->getAdmim())
                        ->withSession(['banned' => false])
                        ->post(route("partials.person.store"), [
                            "person_id" => 0,
                            "person_gender" => $gender->id,
                            "person_marriages" => [
                                [
                                    "role_current" => $roleWrong,
                                    "soulmate" => $personSoulmate->id,
                                    "role_soulmate" => $rolesSoulmate->random()
                                ]
                            ]
                        ]);
                    $response->assertStatus(302);
                }
            }
        }
    }

    public function testInvalidRoleSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 5; $i++) {
            $gender = $genders->random();
            $personSoulmate = $people->random();
            $rolesCurrent = $gender->marriages()->pluck("role_id");
            $rolesSoulmate = $personSoulmate->gender()->first()->marriages()->pluck("role_id");

            $i = 0;
            while ($i < 5) {
                $roleWrong = $this->faker->unique()->randomNumber();
                if (!$rolesSoulmate->contains($roleWrong)) {
                    $i++;
                    $response = $this->actingAs($this->getAdmim())
                        ->withSession(['banned' => false])
                        ->post(route("partials.person.store"), [
                            "person_id" => 0,
                            "person_gender" => $gender->id,
                            "person_marriages" => [
                                [
                                    "role_current" => $rolesCurrent->random(),
                                    "soulmate" => $personSoulmate->id,
                                    "role_soulmate" => $roleWrong
                                ]
                            ]
                        ]);
                    $response->assertStatus(302);
                }
            }
        }
    }

    public function testInvalidSoulmate(): void
    {
        $this->seed();

        $genders = GenderEloquentModel::get();
        for ($i = 0; $i < 5; $i++) {
            $gender = $genders->random();
            $roleCurrent = $gender->marriages()->get()->random();
            $roleSoulmate = $roleCurrent->scope1()->get()->random();

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $roleCurrent->id,
                            "soulmate" => "fake",
                            "role_soulmate" => $roleSoulmate->id
                        ]
                    ]
                ]);
            $response->assertStatus(302);
        }
    }

    /**
     * @dataProvider providerRoleScope
     */
    public function testRoleScope(
        int $id,
        int $genderId,
        int $roleCurrentId,
        int $personSoulmateId,
        int $roleSoulmate
    ): void {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.store"), [
                "person_id" => $id,
                "person_gender" => $genderId,
                "person_marriages" => [
                    [
                        "role_current" => $roleCurrentId,
                        "soulmate" => $personSoulmateId,
                        "role_soulmate" => $roleSoulmate
                    ]
                ]
            ]);
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerRoleScope(): array
    {
        return [
            [1, 2, 5, 2, 5],
            [1, 2, 5, 2, 1],
            [1, 3, 4, 2, 5],
            [1, 2, 14, 2, 5],
        ];
    }

    public function testUnifiedParentAndSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        $genders = GenderEloquentModel::get();

        for ($i = 0; $i < 10; $i++) {
            $gender = $genders->random();
            $roleCurrent = $gender->marriages()->pluck("role_id")->random();
            $person = $people->random();
            $roleParent = $person->gender()->first()->parents()->pluck("parent_id")->random();
            $roleSoulmate = $person->gender()->first()->marriages()->pluck("role_id")->random();

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => 0,
                    "person_gender" => $gender->id,
                    "person_marriages" => [
                        [
                            "role_current" => $roleCurrent,
                            "soulmate" => $person->id,
                            "role_soulmate" => $roleSoulmate
                        ]
                    ],
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

    public function testUnifiedPersonAndSoulmate(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(20)->get();
        for ($i = 0; $i < 10; $i++) {
            $person = $people->random();
            $roleCurrent = $person->gender()->first()->marriages()->pluck("role_id")->random();
            $roleSoulmate = $person->gender()->first()->marriages()->pluck("role_id")->random();

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.store"), [
                    "person_id" => $person->id,
                    "person_gender" => $person->gender()->first()->id,
                    "person_marriages" => [
                        [
                            "role_current" => $roleCurrent,
                            "soulmate" => $person->id,
                            "role_soulmate" => $roleSoulmate
                        ]
                    ],
                ]);
            $response->assertStatus(302);
        }
    }
}
