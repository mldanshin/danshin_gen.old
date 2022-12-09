<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ResourceControllerWrongInternetTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;

    /**
     * @param array|mixed[] $value
     * @dataProvider missingUrlProvider
     */
    public function testMissingUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_internet", $value);
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
                        "name" => "danshin"
                    ]
                ]
            ],
            [
                [
                    [
                        "name" => "danshin"
                    ]
                ]
            ],
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider missingNameProvider
     */
    public function testMissingName(array $value): void
    {
        $this->seed();

        $this->testItem("person_internet", $value);
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
                        "url" => "https://danshin.net"
                    ]
                ]
            ],
            [
                [
                    [
                        "url" => "https://test-go.ru"
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider patternUrlProvider
     */
    public function testPatternUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_internet", $value);
    }

    /**
     * @return array|mixed[]
     */
    public function patternUrlProvider(): array
    {
        return [
            [
                [
                    [
                        "name" => "danshin",
                        "url" => "danshin.net"
                    ],
                    [
                        "name" => "test",
                        "url" => "test-go.ru"
                    ]
                ]
            ],
        ];
    }

    /**
     * @param array|mixed[] $value
     * @dataProvider duplicateUrlProvider
     */
    public function testDuplicateUrl(array $value): void
    {
        $this->seed();

        $this->testItem("person_internet", $value);
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
                        "name" => "danshin 1",
                        "url" => "https://danshin.net"
                    ],
                    [
                        "name" => "danshin 2",
                        "url" => "https://danshin.net"
                    ]
                ]
            ],
        ];
    }
}
