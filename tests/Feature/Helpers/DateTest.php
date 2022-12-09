<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Date as DateHelper;
use Tests\TestCase;

class DateTest extends TestCase
{
    /**
     * @dataProvider birthProvider
     */
    public function testBirth($expected, $actualDate, $actualAge, $actualIsLife): void
    {
        $this->assertMatchesRegularExpression($expected, DateHelper::getBirth($actualDate, $actualAge, $actualIsLife));
    }

    public function birthProvider(): array
    {
        return [
            ["#01.12.2020#", "2020-12-01", null, false],
            ["##", null, null, true],
            ["#01.12.2020 \(2 .+\)#", "2020-12-01", new \DateInterval("P2Y"), true],
            ["#01.12.2020 \(1 .+\)#", "2020-12-01", new \DateInterval("P0Y1M"), true],
            ["#01.12.2020 \(20 .+\)#", "2020-12-01", new \DateInterval("P0Y0M20D"), true],
            ["#01.12.2020#", "2020-12-01", new \DateInterval("P0Y0M20D"), false],
        ];
    }

    /**
     * @dataProvider deathProvider
     */
    public function testDeath($expected, $actualDate, $actualAge, $actualIntervalDeath): void
    {
        $this->assertMatchesRegularExpression(
            $expected,
            DateHelper::getDeath($actualDate, $actualAge, $actualIntervalDeath)
        );
    }

    public function deathProvider(): array
    {
        return [
            ["#01.12.2020#", "2020-12-01", null, null],
            ["##", null, new \DateInterval("P2Y"), new \DateInterval("P12Y")],
            ["#01.12.2020 \(2 .+ 25 .+\)#", "2020-12-01", new \DateInterval("P25Y"), new \DateInterval("P2Y")],
            ["#01.12.2020 \(1 .+\)#", "2020-12-01", null, new \DateInterval("P0Y1M")],
        ];
    }

    /**
     * @dataProvider periodLiveProvider
     */
    public function testPeriodLive(string $expected, string $actualBirthDate, ?string $actualDeathDate): void
    {
        $this->assertEquals(
            $expected,
            DateHelper::periodLive($actualBirthDate, $actualDeathDate)
        );
    }

    public function periodLiveProvider(): array
    {
        return [
            ["(?)", "", null],
            ["(?)", "", null],
            ["(?-?)", "", ""],
            ["(01.01.2000-?)", "2000-01-01", ""],
            ["(01.01.2000-01.??.2020)", "2000-01-01", "2020-??-01"],
            ["(?-01.01.2020)", "", "2020-01-01"],
            ["(01.01.????-??.10.2020)", "????-01-01", "2020-10-??"],
        ];
    }

    /**
     * @dataProvider formatSuccessProvider
     */
    public function testFormatSuccess($expected, $actual): void
    {
        $this->assertEquals($expected, DateHelper::format($actual));
    }

    public function formatSuccessProvider(): array
    {
        return [
            [null, ""],
            ["", ""],
            ["09.01.2000", "2000-01-09"],
            ["09.01.????", "????-01-09"],
        ];
    }

    /**
     * @dataProvider formatWrongProvider
     */
    public function testFormatWrong($actual): void
    {
        $this->expectException(\Exception::class);
        DateHelper::format($actual);
    }

    public function formatWrongProvider(): array
    {
        return [
            ["null"],
            ["blabla"],
            ["2000-01-9"],
            ["????-09"],
            ["????.09.01"],
        ];
    }

    public function testDateIntervalSuccess(): void
    {
        $arrayData = [
            ["60 " . __("date.year.plural"), "P60Y20D"],
            ["1 " . __("date.year.nominative"), "P1Y6M"],
            ["2 " . __("date.year.accusative"), "P2Y6M"],
            ["113 " . __("date.year.plural"), "P113Y"],
            ["121 " . __("date.year.nominative"), "P121Y"],
            ["521 " . __("date.year.nominative"), "P521Y"],
            ["111121 " . __("date.year.nominative"), "P111121Y"],
            ["6 " . __("date.month.plural"), "P6M2D"],
            ["1 " . __("date.month.nominative"), "P1M2D"],
            ["3 " . __("date.month.accusative"), "P3M2D"],
            ["3 " . __("date.day.accusative"), "P3D"],
            ["1 " . __("date.day.nominative"), "P1D"],
            ["20 " . __("date.day.plural"), "P20D"],
        ];

        foreach ($arrayData as $item) {
            $this->assertEquals($item[0], DateHelper::dateInterval(new \DateInterval($item[1])));
        }
    }
}
