<?php

namespace App\Models;

use Illuminate\Support\Collection;

final class PersonShort
{
    /**
     * @param Collection|string[]|null $oldSurname
     */
    public function __construct(
        private int $id,
        private ?string $surname,
        private ?Collection $oldSurname,
        private ?string $name,
        private ?string $patronymic,
        private ?string $birthDate
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return Collection|string[]|null
     */
    public function getOldSurname(): ?Collection
    {
        return $this->oldSurname;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }
}
