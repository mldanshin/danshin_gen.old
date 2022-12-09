<?php

namespace App\View\Events;

final class Event
{
    public function __construct(
        public string $name,
        public string $date,
        public int $personId,
        public string $person,
        public string $calculate
    ) {
    }
}
