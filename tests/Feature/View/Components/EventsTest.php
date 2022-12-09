<?php

namespace Tests\Feature\View\Components;

use App\Models\Events\Birth as BirthModel;
use App\Models\Events\BirthWould as BirthWouldModel;
use App\Models\Events\Death as DeathModel;
use App\Models\Events\Events as EventsModel;
use App\Models\Events\Person as PersonModel;
use App\View\Components\Events as EventsComponent;
use App\View\Events\Event;
use Tests\TestCase;

final class EventsTest extends TestCase
{
    /**
     * @dataProvider pastProvider
     */
    public function testPast(
        EventsModel $model,
        callable $callbackExpected
    ): void {
        $component = new EventsComponent($model);
        $this->assertEquals($callbackExpected(), $component->past[0]);
    }

    /**
     * @return mixed[]
     */
    public function pastProvider(): array
    {
        return [
            [
                new EventsModel(
                    collect([
                        new BirthModel(
                            "2000-01-01",
                            new PersonModel(1, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P2Y")
                        )
                    ]),
                    collect(),
                    collect()
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2000",
                    1,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.birth.fulfilled") . " 2 " . __("date.year.accusative") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect([
                        new BirthWouldModel(
                            "2010-01-01",
                            new PersonModel(2, "Petrov", "Petr", "Ivanovich"),
                            new \DateInterval("P20Y")
                        )
                    ]),
                    collect(),
                    collect()
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2010",
                    2,
                    "Petrov Petr Ivanovich",
                    "(" . "20 " . __("date.year.plural") . " " . __("events.birth.past") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect([
                        new DeathModel(
                            "2010-01-01",
                            new PersonModel(3, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P20Y"),
                            new \DateInterval("P2Y"),
                        )
                    ]),
                    collect(),
                    collect()
                ),
                fn() => new Event(
                    __("events.death.name"),
                    "01.01.2010",
                    3,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.death.passed_age", [
                        "interval" => "2 " . __("date.year.accusative"),
                        "age" => "20 " . __("date.year.plural")
                    ]) . ")"
                ),
            ],
        ];
    }

    /**
     * @dataProvider todayProvider
     */
    public function testToday(
        EventsModel $model,
        callable $callbackExpected
    ): void {
        $component = new EventsComponent($model);
        $this->assertEquals($callbackExpected(), $component->today[0]);
    }

    /**
     * @return array[]
     */
    public function todayProvider(): array
    {
        return [
            [
                new EventsModel(
                    collect(),
                    collect([
                        new BirthModel(
                            "2000-01-01",
                            new PersonModel(1, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P2Y")
                        )
                    ]),
                    collect()
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2000",
                    1,
                    "Ivanov Ivan Ivanovich",
                    "(2 " . __("date.year.accusative") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect([
                        new BirthWouldModel(
                            "2010-01-01",
                            new PersonModel(2, "Petrov", "Petr", "Ivanovich"),
                            new \DateInterval("P20Y")
                        )
                    ]),
                    collect()
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2010",
                    2,
                    "Petrov Petr Ivanovich",
                    "(" . "20 " . __("date.year.plural") . " " . __("events.birth.past") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect([
                        new DeathModel(
                            "2010-01-01",
                            new PersonModel(3, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P20Y"),
                            new \DateInterval("P2Y"),
                        )
                    ]),
                    collect()
                ),
                fn() => new Event(
                    __("events.death.name"),
                    "01.01.2010",
                    3,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.death.passed_age", [
                        "interval" => "2 " . __("date.year.accusative"),
                        "age" => "20 " . __("date.year.plural")
                    ]) . ")"
                )
            ],
        ];
    }

    /**
     * @dataProvider nearestProvider
     */
    public function testNearest(
        EventsModel $model,
        callable $callbackExpected
    ): void {
        $component = new EventsComponent($model);
        $this->assertEquals($callbackExpected(), $component->nearest[0]);
    }

    /**
     * @return array[]
     */
    public function nearestProvider(): array
    {
        return [
            [
                new EventsModel(
                    collect(),
                    collect(),
                    collect([
                        new BirthModel(
                            "2000-01-01",
                            new PersonModel(1, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P2Y")
                        )
                    ])
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2000",
                    1,
                    "Ivanov Ivan Ivanovich",
                    "(". __("events.birth.will_be") . " 2 " . __("date.year.accusative") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect(),
                    collect([
                        new BirthWouldModel(
                            "2010-01-01",
                            new PersonModel(2, "Petrov", "Petr", "Ivanovich"),
                            new \DateInterval("P20Y")
                        )
                    ])
                ),
                fn() => new Event(
                    __("events.birth.name"),
                    "01.01.2010",
                    2,
                    "Petrov Petr Ivanovich",
                    "(" . "20 " . __("date.year.plural") . " " . __("events.birth.past") . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect(),
                    collect([
                        new DeathModel(
                            "2010-01-01",
                            new PersonModel(3, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P20Y"),
                            new \DateInterval("P2Y"),
                        )
                    ])
                ),
                fn() => new Event(
                    __("events.death.name"),
                    "01.01.2010",
                    3,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.death.passed_age", [
                        "interval" => "2 " . __("date.year.accusative"),
                        "age" => "20 " . __("date.year.plural")
                    ]) . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect(),
                    collect([
                        new DeathModel(
                            "2010-01-01",
                            new PersonModel(4, "Ivanov", "Ivan", "Ivanovich"),
                            null,
                            new \DateInterval("P2Y"),
                        )
                    ])
                ),
                fn() => new Event(
                    __("events.death.name"),
                    "01.01.2010",
                    4,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.death.passed", ["interval" => "2 " . __("date.year.accusative")]) . ")"
                ),
            ],
            [
                new EventsModel(
                    collect(),
                    collect(),
                    collect([
                        new DeathModel(
                            "2010-01-01",
                            new PersonModel(5, "Ivanov", "Ivan", "Ivanovich"),
                            null,
                            new \DateInterval("P2Y"),
                        )
                    ])
                ),
                fn() => new Event(
                    __("events.death.name"),
                    "01.01.2010",
                    5,
                    "Ivanov Ivan Ivanovich",
                    "(" . __("events.death.passed", ["interval" => "2 " . __("date.year.accusative")]) . ")"
                ),
            ],
        ];
    }
}
