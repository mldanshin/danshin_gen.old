<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ResourceControllerWrongOldSurnameTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;

    /**
     * @param array|mixed[] $value
     * @dataProvider missingNameProvider
     */
    public function testMissingName(array $value): void
    {
        $this->seed();

        $this->testItem("person_old_surnames", $value);
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
                        "order" => 1
                    ]
                ]
            ],
            [
                [
                    [
                        "order" => 2
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider missingOrderProvider
     */
    public function testMissingOrder(array $value): void
    {
        $this->seed();

        $this->testItem("person_old_surnames", $value);
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
                        "name" => "Ivanov",
                    ]
                ]
            ],
            [
                [
                    [
                        "name" => "Petrov",
                    ]
                ]
            ],
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider duplicateOrderProvider
     */
    public function testDuplicateOrder(array $value): void
    {
        $this->seed();

        $this->testItem("person_old_surnames", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function duplicateOrderProvider(): array
    {
        return [
            [
                [
                    [
                        "name" => "Ivanov",
                        "order" => 1
                    ],
                    [
                        "name" => "Petrov",
                        "order" => 1
                    ]
                ]
            ],
        ];
    }
}
