<?php

namespace App\Models\Events;

final class BirthWould extends Event
{
    public function __construct(
        string $date,
        Person $person,
        private \DateInterval $age
    ) {
        parent::__construct($date, $person);
    }

    public function getAge(): \DateInterval
    {
        return $this->age;
    }
}
