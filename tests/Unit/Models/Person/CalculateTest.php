<?php

namespace Tests\Unit\Models\Person;

use App\Models\Person\Calculate as CalculateModel;
use PHPUnit\Framework\TestCase;

final class CalculateTest extends TestCase
{
    /**
     * @dataProvider createSuccessProvider
     */
    public function testCreateSuccess(
        \DateTime $today,
        string $birthDate,
        ?string $deathDate,
        ?\DateInterval $expectedAge,
        ?\DateInterval $expectedIntervalBirth,
        ?\DateInterval $expectedIntervalDeath,
    ): void {
        $model = new CalculateModel($today, $birthDate, $deathDate);

        $this->assertInstanceOf(CalculateModel::class, $model);
        $this->assertTrue($this->assertEqualsDateInterval($expectedAge, $model->getAge()));
        $this->assertTrue($this->assertEqualsDateInterval($expectedIntervalBirth, $model->getIntervalBirth()));
        $this->assertTrue($this->assertEqualsDateInterval($expectedIntervalDeath, $model->getIntervalDeath()));
    }

    public function createSuccessProvider(): array
    {
        return [
            [
                new \DateTime("2021-10-15"),
                "",
                null,
                null,
                null,
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "",
                "",
                null,
                null,
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "2000-??-10",
                "",
                null,
                null,
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "2000-12-15",
                "????-10-01",
                null,
                new \DateInterval("P20Y10M"),
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "2000-??-15",
                "????-10-01",
                null,
                null,
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "2000-10-10",
                "",
                null,
                new \DateInterval("P21Y5D"),
                null
            ],
            [
                new \DateTime("2021-10-15"),
                "2000-10-10",
                "2020-10-11",
                new \DateInterval("P20Y1D"),
                new \DateInterval("P21Y5D"),
                new \DateInterval("P1Y4D")
            ],
            [
                new \DateTime("2021-10-15"),
                "",
                "2020-10-11",
                null,
                null,
                new \DateInterval("P1Y4D")
            ],
        ];
    }

    /**
     * @dataProvider createWrongProvider
     */
    public function testCreateWrong(
        \DateTime $today,
        ?string $birthDate,
        ?string $deathDate
    ): void {
        $this->expectException(\Exception::class);

        new CalculateModel($today, $birthDate, $deathDate);
    }

    public function createWrongProvider(): array
    {
        return [
            [new \DateTime("2021-10-15"), "2022-01-01", null],
            [new \DateTime("2021-10-15"), "2022-01-01", "2023-01-01"],
            [new \DateTime("2021-10-15"), "", "2023-01-01"],
            [new \DateTime("2021-10-15"), "2023-01-01", "2022-01-01"],
            [new \DateTime("2021-10-15"), "2019-01-01", "2018-01-01"],
        ];
    }

    private function assertEqualsDateInterval(?\DateInterval $interval1, ?\DateInterval $interval2): bool
    {
        if ($interval1 === null && $interval2 === null) {
            return true;
        }

        $seconds1 = $interval1->y * 31536000
            + $interval1->m * 2592000
            + $interval1->d * 86400
            + $interval1->h * 3600
            + $interval1->i * 60
            + $interval1->s;

        $seconds2 = $interval2->y * 31536000
             + $interval2->m * 2592000
             + $interval2->d * 86400
             + $interval2->h * 3600
             + $interval2->i * 60
             + $interval2->s;

        if ($seconds1 === $seconds2) {
            return true;
        } else {
            return false;
        }
    }
}
