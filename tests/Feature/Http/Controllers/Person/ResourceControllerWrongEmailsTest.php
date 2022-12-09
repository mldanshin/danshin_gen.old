<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\Dates;
use Tests\TestCase;

final class ResourceControllerWrongEmailsTest extends TestCase
{
    use Dates;
    use RefreshDatabase;
    use TestingWrongItem;

    /**
     * @param string[] $value
     * @dataProvider missingProvider
     */
    public function testMissing(array $value): void
    {
        $this->seed();

        $this->testItem("person_emails", $value);
    }

    /**
     * @return array[]
     */
    public function missingProvider(): array
    {
        return [
            [
                [
                    ""
                ],
            ],
            [
                [
                    "",
                ]
            ]
        ];
    }

    /**
     * @param string[] $value
     * @dataProvider duplicateProvider
     */
    public function testDuplicate(array $value): void
    {
        $this->seed();

        $this->testItem("person_emails", $value);
    }

    /**
     * @return array[]
     */
    public function duplicateProvider(): array
    {
        return [
            [
                [
                    "max@danshin.net",
                    "max@danshin.net"
                ]
            ],
            [
                [
                    "mail@test-go.ru",
                    "mail@test-go.ru"
                ]
            ]
        ];
    }

    /**
     * @param string[] $value
     * @dataProvider uniqueProvider
     */
    public function testUnique(array $value): void
    {
        $this->seed();

        $this->testItem("person_emails", $value);
    }

    /**
     * @return array[]
     */
    public function uniqueProvider(): array
    {
        return [
            [
                [
                    "mail@danshin.net"
                ]
            ],
            [
                [
                    "natali@fakemail.ru"
                ]
            ]
        ];
    }
}
