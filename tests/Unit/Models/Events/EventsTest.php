<?php

namespace Tests\Unit\Models\Events;

use App\Models\Events\Birth as BirthModel;
use App\Models\Events\BirthWould as BirthWouldModel;
use App\Models\Events\Death as DeathModel;
use App\Models\Events\Events as EventsModel;
use App\Models\Events\Person as PersonModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class EventsTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|Event[] $past
     * @param Collection|Event[] $today
     * @param Collection|Event[] $nearest
     */
    public function testCreate(
        Collection $past,
        Collection $today,
        Collection $nearest
    ): void {
        $model = new EventsModel($past, $today, $nearest);

        $this->assertInstanceOf(EventsModel::class, $model);
        $this->assertEquals($past, $model->getPast());
        $this->assertEquals($today, $model->getToday());
        $this->assertEquals($nearest, $model->getNearest());
    }

    public function createProvider(): array
    {
        return [
            [
                collect([
                    new BirthModel(
                        "2000-01-01",
                        new PersonModel(1, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P10Y")
                    ),
                    new BirthWouldModel(
                        "2010-01-01",
                        new PersonModel(2, "Petrov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y")
                    ),
                    new DeathModel(
                        "2003-01-01",
                        new PersonModel(3, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y"),
                        new \DateInterval("P10Y")
                    )
                ]),
                collect(),
                collect()
            ]
        ];
    }

    /**
     * @dataProvider emptyTrueProvider
     * @param Collection|Event[] $past
     * @param Collection|Event[] $today
     * @param Collection|Event[] $nearest
     */
    public function testEmptyTrue(
        Collection $past,
        Collection $today,
        Collection $nearest
    ): void {
        $model = new EventsModel($past, $today, $nearest);
        $this->assertTrue($model->isEmpty());
    }

    public function emptyTrueProvider(): array
    {
        return [
            [
                collect(),
                collect(),
                collect()
            ]
        ];
    }

     /**
     * @dataProvider emptyFalseProvider
     * @param Collection|Event[] $past
     * @param Collection|Event[] $today
     * @param Collection|Event[] $nearest
     */
    public function testEmptyFalse(
        Collection $past,
        Collection $today,
        Collection $nearest
    ): void {
        $model = new EventsModel($past, $today, $nearest);
        $this->assertFalse($model->isEmpty());
    }

    public function emptyFalseProvider(): array
    {
        return [
            [
                collect([
                    new BirthModel(
                        "2000-01-01",
                        new PersonModel(1, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P10Y")
                    ),
                    new BirthWouldModel(
                        "2010-01-01",
                        new PersonModel(2, "Petrov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y")
                    ),
                    new DeathModel(
                        "2003-01-01",
                        new PersonModel(3, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y"),
                        new \DateInterval("P10Y")
                    )
                ]),
                collect(),
                collect()
            ],
            [
                collect(),
                collect([
                    new BirthWouldModel(
                        "2010-01-01",
                        new PersonModel(1, "Petrov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y")
                    ),
                    new DeathModel(
                        "2003-01-01",
                        new PersonModel(2, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y"),
                        new \DateInterval("P10Y")
                    )
                ]),
                collect()
            ],
            [
                collect(),
                collect(),
                collect([
                    new BirthWouldModel(
                        "2010-01-01",
                        new PersonModel(1, "Petrov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y")
                    ),
                    new DeathModel(
                        "2003-01-01",
                        new PersonModel(2, "Ivanov", "Ivan", "Ivanovich"),
                        new \DateInterval("P15Y"),
                        new \DateInterval("P10Y")
                    )
                ])
            ]
        ];
    }
}
