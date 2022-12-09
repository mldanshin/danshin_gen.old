<?php

namespace Tests\Feature\Repositories\Tree;

use App\Models\Eloquent\Marriage as MarriageEloquent;
use App\Models\Eloquent\ParentChild as ParentChildEloquent;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Toggle as ToggleModel;
use App\Repositories\Tree\Tree as TreeRepository;
use Database\Seeders\Testing\GenderSeeder;
use Database\Seeders\Testing\MarriageRoleSeeder;
use Database\Seeders\Testing\MarriageRoleScopeSeeder;
use Database\Seeders\Testing\ParentRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TreeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $id,
        ?string $parentId,
        PersonShortModel $personTarget,
        ?ToggleModel $toggle,
        FamilyModel $family
    ): void {
        $this->seedPeople();

        $repository = new TreeRepository($id, $parentId);
        $this->assertInstanceOf(TreeRepository::class, $repository);
        $this->assertEquals($toggle, $repository->getToggle());
        $this->assertEquals($personTarget, $repository->get()->getPersonTarget());
        $this->assertEquals($family, $repository->get()->getFamily());
    }

    /**
     * @return array[]
     */
    public function createProvider(): array
    {
        return [
            [
                "1",
                null,
                new PersonShortModel(1, "Surname1", "Name1", "Patronymic1"),
                null,
                new FamilyModel(
                    new PersonModel(
                        1,
                        "Surname1",
                        null,
                        "Name1",
                        "Patronymic1",
                        "",
                        null,
                        true
                    ),
                    collect(),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                10,
                                "Surname10",
                                null,
                                "Name10",
                                "Patronymic10",
                                "",
                                null,
                                false
                            ),
                            collect(),
                            collect(),
                        )
                    ]),
                )
            ],
            [
                "10",
                null,
                new PersonShortModel(10, "Surname10", "Name10", "Patronymic10"),
                new ToggleModel(
                    collect([
                        new PersonShortModel(1, "Surname1", "Name1", "Patronymic1")
                    ]),
                    1
                ),
                new FamilyModel(
                    new PersonModel(
                        1,
                        "Surname1",
                        null,
                        "Name1",
                        "Patronymic1",
                        "",
                        null,
                        false
                    ),
                    collect(),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                10,
                                "Surname10",
                                null,
                                "Name10",
                                "Patronymic10",
                                "",
                                null,
                                true
                            ),
                            collect(),
                            collect(),
                        )
                    ]),
                )
            ],
            [
                "11",
                null,
                new PersonShortModel(11, "Surname11", "Name11", "Patronymic11"),
                new ToggleModel(
                    collect([
                        new PersonShortModel(2, "Surname2", "Name2", "Patronymic2"),
                        new PersonShortModel(3, "Surname3", "Name3", "Patronymic3")
                    ]),
                    2
                ),
                new FamilyModel(
                    new PersonModel(
                        2,
                        "Surname2",
                        null,
                        "Name2",
                        "Patronymic2",
                        "",
                        null,
                        false
                    ),
                    collect([
                        new PersonModel(
                            3,
                            "Surname3",
                            null,
                            "Name3",
                            "Patronymic3",
                            "",
                            null,
                            false
                        ),
                    ]),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                11,
                                "Surname11",
                                null,
                                "Name11",
                                "Patronymic11",
                                "",
                                null,
                                true
                            ),
                            collect(),
                            collect([
                                new FamilyModel(
                                    new PersonModel(
                                        20,
                                        "Surname20",
                                        null,
                                        "Name20",
                                        "Patronymic20",
                                        "",
                                        null,
                                        false
                                    ),
                                    collect(),
                                    collect(),
                                )
                            ]),
                        )
                    ]),
                )
            ],
            [
                "11",
                "3",
                new PersonShortModel(11, "Surname11", "Name11", "Patronymic11"),
                new ToggleModel(
                    collect([
                        new PersonShortModel(2, "Surname2", "Name2", "Patronymic2"),
                        new PersonShortModel(3, "Surname3", "Name3", "Patronymic3")
                    ]),
                    3
                ),
                new FamilyModel(
                    new PersonModel(
                        3,
                        "Surname3",
                        null,
                        "Name3",
                        "Patronymic3",
                        "",
                        null,
                        false
                    ),
                    collect([
                        new PersonModel(
                            2,
                            "Surname2",
                            null,
                            "Name2",
                            "Patronymic2",
                            "",
                            null,
                            false
                        ),
                    ]),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                11,
                                "Surname11",
                                null,
                                "Name11",
                                "Patronymic11",
                                "",
                                null,
                                true
                            ),
                            collect(),
                            collect([
                                new FamilyModel(
                                    new PersonModel(
                                        20,
                                        "Surname20",
                                        null,
                                        "Name20",
                                        "Patronymic20",
                                        "",
                                        null,
                                        false
                                    ),
                                    collect(),
                                    collect(),
                                )
                            ]),
                        )
                    ]),
                )
            ],
            [
                "20",
                null,
                new PersonShortModel(20, "Surname20", "Name20", "Patronymic20"),
                new ToggleModel(
                    collect([
                        new PersonShortModel(11, "Surname11", "Name11", "Patronymic11"),
                    ]),
                    11
                ),
                new FamilyModel(
                    new PersonModel(
                        2,
                        "Surname2",
                        null,
                        "Name2",
                        "Patronymic2",
                        "",
                        null,
                        false
                    ),
                    collect([
                        new PersonModel(
                            3,
                            "Surname3",
                            null,
                            "Name3",
                            "Patronymic3",
                            "",
                            null,
                            false
                        ),
                    ]),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                11,
                                "Surname11",
                                null,
                                "Name11",
                                "Patronymic11",
                                "",
                                null,
                                false
                            ),
                            collect(),
                            collect([
                                new FamilyModel(
                                    new PersonModel(
                                        20,
                                        "Surname20",
                                        null,
                                        "Name20",
                                        "Patronymic20",
                                        "",
                                        null,
                                        true
                                    ),
                                    collect(),
                                    collect(),
                                )
                            ]),
                        )
                    ]),
                )
            ],
            [
                "20",
                "11",
                new PersonShortModel(20, "Surname20", "Name20", "Patronymic20"),
                new ToggleModel(
                    collect([
                        new PersonShortModel(11, "Surname11", "Name11", "Patronymic11"),
                    ]),
                    11
                ),
                new FamilyModel(
                    new PersonModel(
                        2,
                        "Surname2",
                        null,
                        "Name2",
                        "Patronymic2",
                        "",
                        null,
                        false
                    ),
                    collect([
                        new PersonModel(
                            3,
                            "Surname3",
                            null,
                            "Name3",
                            "Patronymic3",
                            "",
                            null,
                            false
                        ),
                    ]),
                    collect([
                        new FamilyModel(
                            new PersonModel(
                                11,
                                "Surname11",
                                null,
                                "Name11",
                                "Patronymic11",
                                "",
                                null,
                                false
                            ),
                            collect(),
                            collect([
                                new FamilyModel(
                                    new PersonModel(
                                        20,
                                        "Surname20",
                                        null,
                                        "Name20",
                                        "Patronymic20",
                                        "",
                                        null,
                                        true
                                    ),
                                    collect(),
                                    collect(),
                                )
                            ]),
                        )
                    ]),
                )
            ],
        ];
    }

    private function seedPeople(): void
    {
        (new GenderSeeder())->run();
        (new MarriageRoleSeeder())->run();
        (new MarriageRoleScopeSeeder())->run();
        (new ParentRoleSeeder())->run();
        PeopleEloquentModel::insert([
            [
                "id" => 1,
                "is_unavailable" => 1,
                "gender_id" => 1,
                "surname" => "Surname1",
                "name" => "Name1",
                "patronymic" => "Patronymic1",
                "birth_date" => "",
                "birth_place" => ""
            ],
            [
                "id" => 2,
                "is_unavailable" => 1,
                "gender_id" => 2,
                "surname" => "Surname2",
                "name" => "Name2",
                "patronymic" => "Patronymic2",
                "birth_date" => "",
                "birth_place" => ""
            ],
            [
                "id" => 3,
                "is_unavailable" => 1,
                "gender_id" => 1,
                "surname" => "Surname3",
                "name" => "Name3",
                "patronymic" => "Patronymic3",
                "birth_date" => "",
                "birth_place" => ""
            ],
            [
                "id" => 10,
                "is_unavailable" => 1,
                "gender_id" => 1,
                "surname" => "Surname10",
                "name" => "Name10",
                "patronymic" => "Patronymic10",
                "birth_date" => "",
                "birth_place" => ""
            ],
            [
                "id" => 11,
                "is_unavailable" => 1,
                "gender_id" => 1,
                "surname" => "Surname11",
                "name" => "Name11",
                "patronymic" => "Patronymic11",
                "birth_date" => "",
                "birth_place" => ""
            ],
            [
                "id" => 20,
                "is_unavailable" => 1,
                "gender_id" => 1,
                "surname" => "Surname20",
                "name" => "Name20",
                "patronymic" => "Patronymic20",
                "birth_date" => "",
                "birth_place" => ""
            ],
        ]);
        ParentChildEloquent::insert([
            [
                "parent_id" => 1,
                "child_id" => 10,
                "parent_role_id" => 1
            ],
            [
                "parent_id" => 2,
                "child_id" => 11,
                "parent_role_id" => 1
            ],
            [
                "parent_id" => 3,
                "child_id" => 11,
                "parent_role_id" => 1
            ],
            [
                "parent_id" => 11,
                "child_id" => 20,
                "parent_role_id" => 1
            ]
        ]);
        MarriageEloquent::insert([
            [
                "person1_id" => 2,
                "person2_id" => 3,
                "role_scope_id" => 1
            ]
        ]);
    }
}
