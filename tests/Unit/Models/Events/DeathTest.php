<?php

namespace Tests\Unit\Models\Events;

use App\Models\Events\Death as DeathModel;
use App\Models\Events\Person as PersonModel;
use PHPUnit\Framework\TestCase;

final class DeathTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $date,
        PersonModel $person,
        \DateInterval $age,
        \DateInterval $interval
    ): void {
        $model = new DeathModel($date, $person, $age, $interval);

        $this->assertInstanceOf(DeathModel::class, $model);
        $this->assertEquals($date, $model->getDate());
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($age, $model->getAge());
        $this->assertEquals($interval, $model->getInterval());
    }

    public function createProvider(): array
    {
        return [
            [
                "2000-01-01",
                new PersonModel(1, "Ivanov", "Ivan", "ivanovich"),
                new \DateInterval("P10Y"),
                new \DateInterval("P2Y")
            ],
            [
                "2012-10-01",
                new PersonModel(2, "Ivanov", "Ivan", "ivanovich"),
                new \DateInterval("P40Y"),
                new \DateInterval("P12Y")
            ]
        ];
    }
}
