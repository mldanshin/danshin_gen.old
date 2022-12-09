<?php

namespace App\Repositories\Events;

use App\Models\Person\Calculate as CalculateModel;
use App\Models\Events\Birth as BirthModel;
use App\Models\Events\BirthWould as BirthWouldModel;
use App\Models\Events\Death as DeathModel;
use App\Models\Events\Event as EventModel;
use App\Models\Events\Events as EventsModel;
use App\Models\Events\Person as PersonModel;
use Illuminate\Support\Collection;

final class Events
{
    private Dirty $dirty;
    private EventsModel $events;
    private \DateTime $dateNearest;

    public function __construct()
    {
        $this->initialize();
    }

    public function get(): EventsModel
    {
        return $this->events;
    }

    private function initialize(): void
    {
        $duration = "P" . config("app.events.nearest") . "D";
        $this->dateNearest = (new \DateTime())->add(new \DateInterval($duration));

        $this->dirty = new Dirty(
            config("app.events.past"),
            new \DateTime(),
            config("app.events.nearest")
        );

        $this->events = new EventsModel(
            $this->getEventsPast(),
            $this->getEventsToday(),
            $this->getEventsNearest()
        );
    }

    /**
     * @return Collection|EventModel[]
     */
    private function getEventsPast(): Collection
    {
        $array = array_merge(
            $this->getBirth("getPastBirth"),
            $this->getDeath("getPastDeath"),
        );
        return collect($this->sort($array));
    }

    /**
     * @return Collection|EventModel[]
     */
    private function getEventsToday(): Collection
    {
        $array = array_merge(
            $this->getBirth("getTodayBirth"),
            $this->getDeath("getTodayDeath"),
        );
        return collect($this->sort($array));
    }

    /**
     * @return Collection|EventModel[]
     */
    private function getEventsNearest(): Collection
    {
        $array = array_merge(
            $this->getBirth("getNearestBirth"),
            $this->getDeath("getNearestDeath"),
        );
        return collect($this->sort($array));
    }

    /**
     * @return array|BirthModel[]|BirthWouldModel[]
     */
    private function getBirth(string $funcName): array
    {
        $array = [];
        $dirtyCollection = $this->dirty->$funcName();
        foreach ($dirtyCollection as $item) {
            $person = new PersonModel(
                $item->id,
                $item->surname,
                $item->name,
                $item->patronymic
            );
            $calculate = new CalculateModel($this->dateNearest, $item->birth_date, $item->death_date);

            if ($calculate->getAge() !== null && $calculate->getIntervalDeath() === null) {
                $array[] = new BirthModel(
                    $item->birth_date,
                    $person,
                    $calculate->getAge()
                );
            } elseif ($calculate->getIntervalBirth() !== null) {
                $array[] = new BirthWouldModel(
                    $item->birth_date,
                    $person,
                    $calculate->getIntervalBirth()
                );
            }
        }
        return $array;
    }

    /**
     * @return array|DeathModel[]
     */
    private function getDeath(string $funcName): array
    {
        $array = [];
        $dirtyCollection = $this->dirty->$funcName();
        foreach ($dirtyCollection as $item) {
            $calculate = new CalculateModel($this->dateNearest, $item->birth_date, $item->death_date);
            $interval = $calculate->getIntervalDeath();
            if ($interval !== null) {
                $array[] = new DeathModel(
                    $item->death_date,
                    new PersonModel(
                        $item->id,
                        $item->surname,
                        $item->name,
                        $item->patronymic
                    ),
                    $calculate->getAge(),
                    $interval
                );
            }
        }
        return $array;
    }

    /**
     * @param EventModel[] $array
     * @return EventModel[]
     */
    private function sort(array $array): array
    {
        $func = function ($item1, $item2) {
            $date1 = substr($item1->getDate(), 5);
            $date2 = substr($item2->getDate(), 5);

            if (preg_match("#^12-\d\d#", $date1)) {
                return 0;
            } else {
                if ($date1 > $date2) {
                    return 1;
                } else {
                    return 0;
                }
            }
        };

        usort($array, $func);
        return $array;
    }
}
