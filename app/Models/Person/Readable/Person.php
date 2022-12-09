<?php

namespace App\Models\Person\Readable;

use App\Models\DefineLiveTrait;
use App\Models\PersonShort;
use App\Models\Person\Calculate;
use Illuminate\Support\Collection;

final class Person
{
    use DefineLiveTrait;

    private ?\DateInterval $age = null;
    private ?\DateInterval $deathDateInterval = null;
    private bool $isLiveProperty;

    /**
     * @param Collection|string[]|null $oldSurname
     * @param Collection|string[]|null $activities
     * @param Collection|string[]|null $emails
     * @param Collection|Internet[]|null $internet
     * @param Collection|string[]|null $phones
     * @param Collection|Residence[]|null $residences
     * @param Collection|ParentModel[]|null $parents
     * @param Collection|Marriage[]|null $marriages
     * @param Collection|PersonShort[]|null $children
     * @param Collection|PersonShort[]|null $brothersSisters
     * @param Collection|Photo[]|null $photo
     */
    public function __construct(
        private int $id,
        private bool $isUnavailableProperty,
        private int $genderId,
        private string $surname,
        private ?Collection $oldSurname,
        private string $name,
        private ?string $patronymic,
        private string $birthDate,
        private string $birthPlace,
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
        private ?Collection $children,
        private ?Collection $brothersSisters,
        private ?Collection $photo,
        \DateTime $today
    ) {
        $this->isLiveProperty = $this->getLive($this->deathDate);

        $calculate = new Calculate(
            $today,
            $this->birthDate,
            $this->deathDate
        );
        $this->age = $calculate->getAge();
        $this->deathDateInterval = $calculate->getIntervalDeath();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isUnavailable(): bool
    {
        return $this->isUnavailableProperty;
    }

    public function isLive(): bool
    {
        return $this->isLiveProperty;
    }

    public function getGenderId(): int
    {
        return $this->genderId;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return Collection|string[]|null $oldSurname
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

    /**
     * the period from the date of birth to the present,
     * or until the date of death, if the person died
     */
    public function getAge(): ?\DateInterval
    {
        return $this->age;
    }

    public function getBirthPlace(): string
    {
        return $this->birthPlace;
    }

    public function getDeathDate(): ?string
    {
        return $this->deathDate;
    }

    /**
     * the period from the date of death to the present
     */
    public function getDeathDateInterval(): ?\DateInterval
    {
        return $this->deathDateInterval;
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
     * @return Collection|PersonShort[]|null
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * @return Collection|PersonShort[]|null
     */
    public function getBrothersSisters(): ?Collection
    {
        return $this->brothersSisters;
    }

    /**
     * @return Collection|Photo[]|null
     */
    public function getPhoto(): ?Collection
    {
        return $this->photo;
    }
}
