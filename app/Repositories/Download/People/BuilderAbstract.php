<?php

namespace App\Repositories\Download\People;

use App\Models\Eloquent\People as PeopleEloquent;
use App\Models\Person\Readable\Person as PersonModel;
use App\Repositories\Download\People\FileSystem;
use App\Repositories\Person\Readable\Person as PersonRepository;
use Illuminate\Support\Collection;

abstract class BuilderAbstract
{
    /**
     * @var Collection|PersonModel[] $people
     */
    private Collection $people;

    protected function __construct(
        protected FileSystem $fileSystem,
        private PersonRepository $personRepository
    ) {
    }

    abstract public function getPeoplePath(): string;
    abstract public function getPersonPath(string $id): string;

    protected function getPerson(int $id): PersonModel
    {
        return $this->personRepository->getById($id);
    }

    /**
     * @return Collection|PersonModel[]
     */
    protected function getPeople(): Collection
    {
        if (!isset($this->people)) {
            $peopleId = PeopleEloquent::orderBy("surname")
            ->orderBy("name")
            ->orderBy("patronymic")
            ->pluck("id");

            $this->people = collect();
            foreach ($peopleId as $personId) {
                $this->people->add($this->personRepository->getById($personId));
            }
        }

        return $this->people;
    }
}
