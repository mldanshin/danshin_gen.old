<?php

namespace App\View\Components;

use App\Helpers\Date;
use App\Helpers\Person;
use App\Models\Events\Birth as BirthModel;
use App\Models\Events\BirthWould as BirthWouldModel;
use App\Models\Events\Death as DeathModel;
use App\Models\Events\Events as EventsModel;
use App\Models\Events\Person as PersonModel;
use App\View\Events\EventNotification as EventView;
use Illuminate\View\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

final class EventsNotification extends Component
{
    public bool $isEmpty;
    /**
     * @var Collection|EventView[] $past
     */
    public Collection $past;

    /**
     * @var Collection|EventView[] $today
     */
    public Collection $today;

    /**
     * @var Collection|EventView[] $nearest
     */
    public Collection $nearest;

    public function __construct(EventsModel $model)
    {
        $this->initialize($model);
    }

    public function render(): View|Factory
    {
        return view('components.events-notification');
    }

    private function initialize(EventsModel $model): void
    {
        $this->isEmpty = $model->isEmpty();

        $this->past = collect();
        foreach ($model->getPast() as $item) {
            $this->past->add($this->past($item));
        }

        $this->today = collect();
        foreach ($model->getToday() as $item) {
            $this->today->add($this->today($item));
        }

        $this->nearest = collect();
        foreach ($model->getNearest() as $item) {
            $this->nearest->add($this->nearest($item));
        }
    }

    /**
     * @throws \Exception
     */
    private function past(BirthModel|BirthWouldModel|DeathModel $event): EventView
    {
        switch ($event::class) {
            case BirthModel::class:
                return $this->getBirth(
                    $event,
                    __("events.birth.fulfilled") . " " . (($event->getAge() === null) ? "" : Date::dateInterval($event->getAge()))
                );
            case BirthWouldModel::class:
                return $this->getBirthWould($event);
            case DeathModel::class:
                return $this->getDeath($event);
            default:
                throw new \Exception("invalid date type");
        }
    }

    /**
     * @throws \Exception
     */
    private function today(BirthModel|BirthWouldModel|DeathModel $event): EventView
    {
        switch ($event::class) {
            case BirthModel::class:
                return $this->getBirth(
                    $event,
                    ($event->getAge() === null) ? "" : Date::dateInterval($event->getAge())
                );
            case BirthWouldModel::class:
                return $this->getBirthWould($event);
            case DeathModel::class:
                return $this->getDeath($event);
            default:
                throw new \Exception("invalid date type");
        }
    }

    /**
     * @throws \Exception
     */
    private function nearest(BirthModel|BirthWouldModel|DeathModel $event): EventView
    {
        switch ($event::class) {
            case BirthModel::class:
                return $this->getBirth(
                    $event,
                    __("events.birth.will_be") . " " . (($event->getAge() === null) ? "" : Date::dateInterval($event->getAge()))
                );
            case BirthWouldModel::class:
                return $this->getBirthWould($event);
            case DeathModel::class:
                return $this->getDeath($event);
            default:
                throw new \Exception("invalid date type");
        }
    }

    private function getBirth(BirthModel $event, string $calculate): EventView
    {
        return $this->createView(
            __("events.birth.name"),
            $event->getDate(),
            $event->getPerson(),
            $calculate
        );
    }

    private function getBirthWould(BirthWouldModel $event): EventView
    {
        return $this->createView(
            __("events.birth.name"),
            $event->getDate(),
            $event->getPerson(),
            Date::dateInterval($event->getAge()) . " " .  __("events.birth.past")
        );
    }

    private function getDeath(DeathModel $event): EventView
    {
        $calculate = "";
        if ($event->getAge() === null) {
            $calculate = __("events.death.passed", [
                "interval" => Date::dateInterval($event->getInterval())
            ]);
        } else {
            $calculate =  __("events.death.passed_age", [
                "interval" => Date::dateInterval($event->getInterval()),
                "age" => Date::dateInterval($event->getAge())
            ]);
        }
        return $this->createView(
            __("events.death.name"),
            $event->getDate(),
            $event->getPerson(),
            $calculate
        );
    }

    private function createView(
        string $nameEvent,
        string $date,
        PersonModel $person,
        string $calculate
    ): EventView {
        return new EventView(
            $nameEvent,
            Date::format($date),
            $person->getId(),
            Person::surname($person->getSurname()) . " " . Person::name($person->getName()) . " " . Person::patronymic($person->getPatronymic()),
            "(" . $calculate . ")"
        );
    }
}
