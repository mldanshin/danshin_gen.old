<?php

namespace Tests\Unit\Models\Events;

use App\Models\Events\Birth as BirthModel;
use App\Models\Events\Person as PersonModel;
use PHPUnit\Framework\TestCase;

final class BirthTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $date,
        PersonModel $person,
        \DateInterval $age
    ): void {
        $model = new BirthModel($date, $person, $age);

        $this->assertInstanceOf(BirthModel::class, $model);
        $this->assertEquals($date, $model->getDate());
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($age, $model->getAge());
    }

    public function createProvider(): array
    {
        return [
            [
                "2000-01-01",
                new PersonModel(1, "Ivanov", "Ivan", "ivanovich"),
                new \DateInterval("P10Y")
            ],
            [
                "2012-10-01",
                new PersonModel(2, "Ivanov", "Ivan", "ivanovich"),
                new \DateInterval("P40Y")
            ]
        ];
    }
}
