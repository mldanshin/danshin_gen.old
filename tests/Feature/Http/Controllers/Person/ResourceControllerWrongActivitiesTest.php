<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ResourceControllerWrongActivitiesTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;

    /**
     * @param array|mixed[] $value
     * @dataProvider missingProvider
     */
    public function testMissing(array $value): void
    {
        $this->seed();

        $this->testItem("person_activities", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function missingProvider(): array
    {
        return [
            [
                [
                    "",
                ]
            ],
            [
                [
                    ""
                ]
            ]
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider duplicateProvider
     */
    public function testDuplicate(array $value): void
    {
        $this->seed();

        $this->testItem("person_activities", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function duplicateProvider(): array
    {
        return [
            [
                [
                    "worker",
                    "worker"
                ],
                [
                    "employee",
                    "employee"
                ]
            ]
        ];
    }
}
