<?php

namespace App\Models\Person\Readable;

final class Residence
{
    public function __construct(
        private string $name,
        private ?string $date
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
}
