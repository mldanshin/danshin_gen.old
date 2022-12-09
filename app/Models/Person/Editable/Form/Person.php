<?php

namespace App\Models\Person\Editable\Form;

use App\Models\DefineLiveTrait;
use App\Models\Person\Editable\Internet;
use App\Models\Person\Editable\OldSurname;
use App\Models\Person\Editable\Photo;
use App\Models\Person\Editable\Residence;
use Illuminate\Support\Collection;

final class Person
{
    use DefineLiveTrait;

    private bool $isLiveProperty;

    /**
     * @param Collection|OldSurname[] $oldSurname
     * @param Collection|string[] $activities
     * @param Collection|string[] $emails
     * @param Collection|Internet[] $internet
     * @param Collection|string[] $phones
     * @param Collection|Residence[] $residences
     * @param Collection|Photo[] $photo
     */
    public function __construct(
        private int $id,
        private bool $isUnavailableProperty,
        private Gender $gender,
        private string $surname,
        private Collection $oldSurname,
        private string $name,
        private ?string $patronymic,
        private string $birthDate,
        private string $birthPlace,
        private ?string $deathDate,
        private string $burialPlace,
        private string $note,
        private Collection $activities,
        private Collection $emails,
        private Collection $internet,
        private Collection $phones,
        private Collection $residences,
        private Parents $parents,
        private Marriages $marriages,
        private Collection $photo
    ) {
        $this->isLiveProperty = $this->getLive($this->deathDate);
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

    public function getGender(): Gender
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
    public function getOldSurname(): Collection
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

    public function getDeathDate(): string
    {
        if ($this->deathDate === null) {
            return "";
        } else {
            return $this->deathDate;
        }
    }

    public function getBurialPlace(): string
    {
        return $this->burialPlace;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @return Collection|string[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    /**
     * @return Collection|string[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    /**
     * @return Collection|Internet[]
     */
    public function getInternet(): Collection
    {
        return $this->internet;
    }

    /**
     * @return Collection|string[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @return Collection|Residence[]
     */
    public function getResidences(): Collection
    {
        return $this->residences;
    }

    public function getParents(): Parents
    {
        return $this->parents;
    }

    public function getMarriages(): Marriages
    {
        return $this->marriages;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }
}
