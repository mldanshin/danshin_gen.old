<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\Dates as DatesDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongResidencesTest extends TestCase
{
    use DatesDataProvider;
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;
    use UserDataProvider;

    /**
     * @param array|mixed[] $value
     * @dataProvider missingNameProvider
     */
    public function testMissingName(array $value): void
    {
        $this->seed();

        $this->testItem("person_residences", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function missingNameProvider(): array
    {
        return [
            [
                [
                    [
                        "date" => "2000-01-01"
                    ]
                ]
            ],
            [
                [
                    [
                        "date" => "2020-01-01"
                    ]
                ]
            ],
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider duplicateNameProvider
     */
    public function testDuplicateName(array $value): void
    {
        $this->seed();

        $this->testItem("person_residences", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function duplicateNameProvider(): array
    {
        return [
            [
                [
                    [
                        "name" => "Kemerovo",
                        "date" => "2000-01-01"
                    ],
                    [
                        "name" => "Kemerovo",
                        "date" => "2010-01-01"
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider duplicateDateProvider
     */
    public function testDuplicateDate(array $value): void
    {
        $this->seed();

        $this->testItem("person_residences", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function duplicateDateProvider(): array
    {
        return [
            [
                [
                    [
                        "name" => "Kemerovo",
                        "date" => "2000-01-01"
                    ],
                    [
                        "name" => "Novosibirsk",
                        "date" => "2000-01-01"
                    ]
                ]
            ]
        ];
    }

    public function testPatternDate(): void
    {
        $this->seed();

        for ($i = 0; $i < 10; $i++) {
            $this->testItem(
                "person_residences",
                [
                    [
                        "name" => "Kemerovo",
                        "date" => $this->faker->randomElement($this->getDatePatternWrong())
                    ]
                ]
            );
        }
    }

    /**
     * @dataProvider betweenDatesProvider
     */
    public function testBetweenDates(string $birth, string $death, string $residence): void
    {
        $this->seed();

        $gender = GenderEloquentModel::value("id");
        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.store"), [
                "person_id" => 0,
                "person_gender" => $gender,
                "person_birth_date" => $birth,
                "person_death_date" => $death,
                "person_residences" => [
                    [
                        "name" => "Kemerovo",
                        "date" => $residence
                    ]
                ]
            ]);
        $response->assertStatus(302);
    }

    /**
     * @return array|mixed[]
     */
    public function betweenDatesProvider(): array
    {
        return [
            ["2000-01-01", "2020-01-01", "1999-01-01"],
            ["2000-01-01", "2020-01-01", "2021-01-01"],
            ["", "2020-01-01", "2021-01-01"],
            ["2000-01-01", "", "1999-01-01"],
        ];
    }
}
