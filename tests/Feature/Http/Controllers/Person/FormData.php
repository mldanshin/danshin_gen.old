<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\DataProvider\Dates;
use Tests\DataProvider\Photo as PhotoDataProvider;

trait FormData
{
    use Dates;
    use PhotoDataProvider;
    use WithFaker;

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @return array|mixed[]
     */
    private function getFormData(int $id, Collection $people, GenderEloquentModel $gender, ?string $urlPhoto): array
    {
        $array = [
            "person_id" => $id,
            "person_gender" => $gender->id
        ];

        $this->addItem(
            $array,
            "person_unavailable",
            $this->faker->randomElement(["on", "1", "yes", "true", null])
        );

        $this->addItem(
            $array,
            "person_surname",
            $this->faker->randomElement([$this->faker->lastName(), null])
        );

        $this->addItem(
            $array,
            "person_old_surnames",
            $this->faker->randomElement([
                [
                    [
                        "name" => $this->faker->unique()->lastName(),
                        "order" => $this->faker->unique()->numberBetween(1, 1000)
                    ],
                    [
                        "name" => $this->faker->unique()->lastName(),
                        "order" => $this->faker->unique()->numberBetween(1, 1000)
                    ],
                    [
                        "name" => $this->faker->unique()->lastName(),
                        "order" => $this->faker->unique()->numberBetween(1, 1000)
                    ]
                ],
                null
            ])
        );

        $this->addItem(
            $array,
            "person_name",
            $this->faker->randomElement([$this->faker->firstName(), null])
        );

        $this->addItem(
            $array,
            "person_patronymic",
            $this->faker->randomElement([$this->faker->firstName(), null])
        );

        $this->addItem(
            $array,
            "person_birth_date",
            $this->faker->randomElement([$this->faker->randomElement($this->getBirthDate()), "", null])
        );

        $this->addItem(
            $array,
            "person_birth_place",
            $this->faker->randomElement([$this->faker->city(), null])
        );

        $this->addItem(
            $array,
            "person_death_date",
            $this->faker->randomElement([$this->faker->randomElement($this->getDeathDate()), "", null])
        );

        $this->addItem(
            $array,
            "person_burial_place",
            $this->faker->randomElement([$this->faker->city(), null])
        );

        $this->addItem(
            $array,
            "person_note",
            $this->faker->randomElement([$this->faker->text(), null])
        );

        $this->addItem(
            $array,
            "person_activities",
            $this->faker->randomElement([
                [
                    $this->faker->unique()->text(),
                    $this->faker->unique()->text()
                ],
                null
            ])
        );

        $this->addItem(
            $array,
            "person_internet",
            $this->faker->randomElement([
                [
                    [
                        "name" => $this->faker->text(),
                        "url" => $this->faker->unique()->url()
                    ],
                    [
                        "name" => $this->faker->text(),
                        "url" => $this->faker->unique()->url()
                    ]
                ],
                null
            ])
        );

        $this->addItem(
            $array,
            "person_emails",
            $this->faker->randomElement([
                [
                    $this->faker->unique()->email()
                ],
                null
            ])
        );

        $this->addItem(
            $array,
            "person_phones",
            $this->faker->randomElement([
                [
                    (string)$this->faker->randomNumber(9),
                    (string)$this->faker->randomNumber(9)
                ],
                null
            ])
        );

        $this->addItem(
            $array,
            "person_residences",
            $this->faker->randomElement([
                [
                    [
                        "name" => $this->faker->address(),
                        "date" => $this->faker->randomElement(array_merge($this->getDateBetween(), [""]))
                    ]
                ],
                null
            ])
        );

        $parents = $this->getParents($id, $people);
        $this->addItem(
            $array,
            "person_parents",
            $parents
        );

        $this->addItem(
            $array,
            "person_marriages",
            $this->getMarriages($id, $people, $gender, array_map(
                fn($item) => $item["person"],
                $parents
            ))
        );

        /**
         * it is not possible to test when the id of the person is not known
         */
        if ($urlPhoto !== null) {
            $this->addItem(
                $array,
                "person_photo",
                $this->faker->randomElement([
                    [
                        [
                            "url" => $urlPhoto,
                            "path_relative" => $urlPhoto,
                            "date" => $this->faker->randomElement(array_merge($this->getDateBetween(), [""])),
                            "order" => $this->faker->unique()->numberBetween(1, 1000)
                        ]
                    ],
                    null
                ])
            );
        }

        return $array;
    }

    /**
     * @param array|mixed[] $array
     */
    private function addItem(array &$array, string $field, mixed $value): void
    {
        if ($value !== null) {
            $array[$field] = $value;
        }
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @return array|mixed[]|null
     */
    private function getParents(int $id, Collection $people): ?array
    {
        $people = $people->except($id);
        if ($people->count() > 0) {
            $parent = $people->random();
            $role = $parent->gender()->first()->parents()->get()->random()->id;

            return [
                [
                    "person" => $parent->id,
                    "role" => $role
                ]
            ];
        } else {
            return null;
        }
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @param Collection|int[] $parents
     * @return array|mixed[]|null
     */
    private function getMarriages(
        int $id,
        Collection $people,
        GenderEloquentModel $gender,
        array $parents
    ): ?array {
        if ($people->count() > 0) {
            $currentRole = $gender->marriages()->get()->random();

            $soulmateRole = $currentRole->scope1()->get()->random();

            $soulmateCandidates = $soulmateRole->role2()->first()
                ->genders()->get()->random()
                ->people()->get();
            if ($soulmateCandidates->count() > 0) {
                $soulmate = $soulmateCandidates->except(array_merge($parents, [$id]));
                if ($soulmate->count() > 0) {
                    return [
                        [
                            "role_current" => $currentRole->id,
                            "soulmate" => $soulmate->random()->id,
                            "role_soulmate" => $soulmateRole->role2_id
                        ]
                    ];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
