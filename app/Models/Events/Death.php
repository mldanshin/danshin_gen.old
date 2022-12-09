<?php

namespace App\Models\Events;

final class Death extends Event
{
    public function __construct(
        private string $date,
        private Person $person,
        private ?\DateInterval $age,
        private \DateInterval $interval
    ) {
        parent::__construct($date, $person);
    }

    public function getAge(): ?\DateInterval
    {
        return $this->age;
    }

    public function getInterval(): \DateInterval
    {
        return $this->interval;
    }
}
