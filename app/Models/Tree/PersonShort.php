<?php

namespace App\Models\Tree;

final class PersonShort
{
    public function __construct(
        private int $id,
        private ?string $surname,
        private ?string $name,
        private ?string $patronymic,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }
}
