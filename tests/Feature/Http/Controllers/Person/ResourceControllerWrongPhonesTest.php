<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ResourceControllerWrongPhonesTest extends TestCase
{
    use RefreshDatabase;
    use TestingWrongItem;

    /**
     * @param string[] $value
     * @dataProvider missingProvider
     */
    public function testMissing(array $value): void
    {
        $this->seed();

        $this->testItem("person_phones", $value);
    }

    /**
     * @return array[]
     */
    public function missingProvider(): array
    {
        return [
            [
                [
                    "",
                    ""
                ]
            ],
            [
                [
                    null
                ]
            ]
        ];
    }

    /**
     * @param string[] $value
     * @dataProvider patternProvider
     */
    public function testPattern(array $value): void
    {
        $this->seed();

        $this->testItem("person_phones", $value);
    }

    /**
     * @return array[]
     */
    public function patternProvider(): array
    {
        return [
            [
                [
                    "333444999",
                    "qq999sss3331122"
                ],
            ],
            [
                [
                    "tel9993331000",
                    "23456789"
                ]
            ],
            [
                [
                    "9993331000tel",
                    "987654321"
                ]
            ],
            [
                [
                    "999-333-1000",
                    "+987654321"
                ]
            ],
            [
                [
                    "+999-333-1000",
                    "999999999999999999999999999999999999987654321"
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

        $this->testItem("person_phones", $value);
    }

    /**
     * @return array[]
     */
    public function duplicateProvider(): array
    {
        return [
            [
                [
                    "7772001010",
                    "7772001010"
                ]
            ],
            [
                [
                    "7773001010",
                    "7773001010"
                ]
            ],
        ];
    }

        /**
     * @param string[] $value
     * @dataProvider uniqueProvider
     */
    public function testUnique(array $value): void
    {
        $this->seed();

        $this->testItem("person_phones", $value);
    }

    /**
     * @return array[]
     */
    public function uniqueProvider(): array
    {
        return [
            [
                [
                    "9991112222"
                ]
            ],
            [
                [
                    "8885552222"
                ]
            ],
        ];
    }
}
