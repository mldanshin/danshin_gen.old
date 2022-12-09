<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\Dates as DatesDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongPhotoTest extends TestCase
{
    use DatesDataProvider;
    use RefreshDatabase;
    use TestingWrongItem;
    use WithFaker;
    use UserDataProvider;

    /**
     * @dataProvider missingUrlProvider
     */
    public function testMissingUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_photo", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function missingUrlProvider(): array
    {
        return [
            [
                [
                    [
                        "order" => 1
                    ]
                ]
            ],
            [
                [
                    [
                        "date" => "2000-01-01",
                        "order" => 2
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider missingOrderProvider
     */
    public function testMissingOrder(array $value): void
    {
        $this->seed();

        $this->testItem("person_photo", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function missingOrderProvider(): array
    {
        return [
            [
                [
                    [
                        "url" => "photo/t/qw.jpg",
                    ]
                ]
            ],
            [
                [
                    [
                        "url" => "photo/t/qw.jpg",
                        "date" => "2000-01-01"
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider invalidUrlProvider
     */
    public function testInvalidUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_photo", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function invalidUrlProvider(): array
    {
        return [
            [
                [
                    [
                        "url" => "",
                        "order" => 1
                    ]
                ]
            ],
            [
                [
                    [
                        "url" => "",
                        "date" => "2000-01-01",
                        "order" => 2
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider invalidOrderProvider
     */
    public function testInvaliOrder(array $value): void
    {
        $this->seed();

        $this->testItem("person_photo", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function invalidOrderProvider(): array
    {
        return [
            [
                [
                    [
                        "url" => "photo/t/qw.jpg",
                        "order" => -100
                    ]
                ]
            ],
            [
                [
                    [
                        "url" => "photo/t/qw.jpg",
                        "date" => "2000-01-01",
                        "order" => 0
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider duplicateUrlProvider
     */
    public function testDuplicateUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_photo", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function duplicateUrlProvider(): array
    {
        return [
            [
                [
                    [
                        "url" => "photo/t/qw.jpg",
                        "order" => 1
                    ],
                    [
                        "url" => "photo/t/qw.jpg",
                        "date" => "2000-01-01",
                        "order" => 1
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
                "person_photo",
                [
                    [
                        "url" => "photo/t/qw.jpg",
                        "date" => $this->faker->randomElement($this->getDatePatternWrong()),
                        "order" => 1
                    ]
                ]
            );
        }
    }

    /**
     * @dataProvider betweenDatesProvider
     */
    public function testBetweenDates(string $birth, string $death, string $photo): void
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
                "person_photo" => [
                    [
                        "url" => "photo/t/qw.jpg",
                        "date" => $photo,
                        "order" => 1
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
