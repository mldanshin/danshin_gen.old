<?php

namespace App\Models\Person\Editable\Request;

use App\Models\Person\Editable\Internet;
use App\Models\Person\Editable\OldSurname;
use App\Models\Person\Editable\Photo;
use App\Models\Person\Editable\Residence;
use Illuminate\Support\Collection;

final class Person
{
    private string $surname;
    private string $name;
    private string $birthDate;
    private string $birthPlace;

    /**
     * @param Collection|OldSurname[]|null $oldSurname
     * @param Collection|string[]|null $activities
     * @param Collection|string[]|null $emails
     * @param Collection|Internet[]|null $internet
     * @param Collection|string[]|null $phones
     * @param Collection|Residence[]|null $residences
     * @param Collection|ParentModel[]|null $parents
     * @param Collection|Marriage[]|null $marriages
     * @param Collection|Photo[]|null $photo
     */
    public function __construct(
        private int $id,
        private bool $isUnavailableProperty,
        bool $isLive,
        private int $gender,
        ?string $surname,
        private ?Collection $oldSurname,
        ?string $name,
        private ?string $patronymic,
        ?string $birthDate,
        ?string $birthPlace,
        private ?string $deathDate,
        private ?string $burialPlace,
        private ?string $note,
        private ?Collection $activities,
        private ?Collection $emails,
        private ?Collection $internet,
        private ?Collection $phones,
        private ?Collection $residences,
        private ?Collection $parents,
        private ?Collection $marriages,
        private ?Collection $photo,
    ) {
        $this->surname = ($surname === null) ? "" : $surname;
        $this->name = ($name === null) ? "" : $name;
        $this->birthDate = ($birthDate === null) ? "" : $birthDate;
        $this->birthPlace = ($birthPlace === null) ? "" : $birthPlace;
        if ($isLive === true) {
            $this->deathDate = null;
        } elseif ($isLive === false && $this->deathDate === null) {
            $this->deathDate = "";
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isUnavailable(): bool
    {
        return $this->isUnavailableProperty;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return Collection|OldSurname[] $oldSurname
     */
    public function getOldSurname(): ?Collection
    {
        return $this->oldSurname;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function getBirthPlace(): string
    {
        return $this->birthPlace;
    }

    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }

    public function getBurialPlace(): ?string
    {
        return $this->burialPlace;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @return Collection|string[]|null
     */
    public function getActivities(): ?Collection
    {
        return $this->activities;
    }

    /**
     * @return Collection|string[]|null
     */
    public function getEmails(): ?Collection
    {
        return $this->emails;
    }

    /**
     * @return Collection|Internet[]|null
     */
    public function getInternet(): ?Collection
    {
        return $this->internet;
    }

    /**
     * @return Collection|string[]|null
     */
    public function getPhones(): ?Collection
    {
        return $this->phones;
    }

    /**
     * @return Collection|Residence[]|null
     */
    public function getResidences(): ?Collection
    {
        return $this->residences;
    }

    /**
     * @return Collection|ParentModel[]|null
     */
    public function getParents(): ?Collection
    {
        return $this->parents;
    }

    /**
     * @return Collection|Marriage[]|null
     */
    public function getMarriages(): ?Collection
    {
        return $this->marriages;
    }

    /**
     * @return Collection|Photo[]|null
     */
    public function getPhoto(): ?Collection
    {
        return $this->photo;
    }
}
