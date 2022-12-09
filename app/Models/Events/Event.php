<?php

namespace App\Models\Events;

abstract class Event
{
    public function __construct(
        private string $date,
        private Person $person
    ) {
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
