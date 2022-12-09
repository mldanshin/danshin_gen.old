<?php

namespace App\Repositories\Tree;

use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Toggle as ToggleModel;
use App\Models\Tree\Tree as TreeModel;
use Illuminate\Support\Collection;

final class Tree
{
    private int $id;
    private ?int $parentId;
    private TreeModel $treeModel;
    private ?ToggleModel $toggleModel;

    public function __construct(string $id, ?string $parentId)
    {
        $this->id = (int)$id;

        if ($parentId === null) {
            $this->parentId = $this->getParentId($this->id);
        } else {
            $this->parentId = (int)$parentId;
        }
    }

    public function get(): TreeModel
    {
        if (!isset($this->treeModel)) {
            $this->initializeTree();
        }
        return $this->treeModel;
    }

    public function getToggle(): ?ToggleModel
    {
        if (!isset($this->toggleModel)) {
            $this->initializeToggle();
        }
        return $this->toggleModel;
    }

    private function initializeTree(): void
    {
        $personRootFamily = $this->getPersonRootFamily($this->parentId);

        $this->treeModel = new TreeModel(
            $this->getPersonShort($this->id),
            $this->getFamily($personRootFamily)
        );
    }

    private function initializeToggle(): void
    {
        if ($this->parentId !== null) {
            $this->toggleModel = new ToggleModel(
                $this->getParentsToggle($this->id),
                $this->parentId
            );
        } else {
            $this->toggleModel = null;
        }
    }

    private function getPersonRootFamily(?int $parentId): int
    {
        if ($parentId === null) {
            return $this->id;
        } else {
            $root = $this->getParentId($parentId);
            if ($root === null) {
                return $parentId;
            } else {
                return $root;
            }
        }
    }

    private function getPersonById(int $id): PersonModel
    {
        return $this->getPersonByEloquent(
            $this->getPersonEloquentById($id)
        );
    }

    private function getPersonEloquentById(int $id): PeopleEloquentModel
    {
        return PeopleEloquentModel::select("id", "surname", "name", "patronymic", "birth_date", "death_date")
            ->where("id", $id)
            ->first();
    }

    private function getPersonByEloquent(PeopleEloquentModel $person): PersonModel
    {
        return new PersonModel(
            $person->id,
            $person->surname,
            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy("_order")->pluck("surname") : null,
            $person->name,
            $person->patronymic,
            $person->birth_date,
            $person->death_date,
            ($this->id === $person->id) ? true : false
        );
    }

    private function getFamily(int $id): FamilyModel
    {
        $person = $this->getPersonEloquentById($id);

        return new FamilyModel(
            $this->getPersonByEloquent($person),
            $this->getMarriage($id),
            $this->getChildrens($person)
        );
    }

    /**
     * @return Collection|PersonModel[]
     */
    private function getMarriage(int $personId): Collection
    {
        $array = [];

        $collection1 = MarriageEloquentModel::where("person1_id", $personId)->pluck("person2_id");
        $collection1->each(
            function ($item) use (&$array) {
                $array[] = $this->getPersonById($item);
            }
        );

        $collection2 = MarriageEloquentModel::where("person2_id", $personId)->pluck("person1_id");
        $collection2->each(
            function ($item) use (&$array) {
                $array[] = $this->getPersonById($item);
            }
        );

        return collect($array);
    }

    /**
     * @return Collection|FamilyModel[]
     */
    private function getChildrens(PeopleEloquentModel $person): Collection
    {
        $array = [];

        $childrensId = $person->childrens()->pluck("child_id");
        foreach ($childrensId as $id) {
            $array[] = $this->getFamily($id);
        }

        return collect($array);
    }

    private function getParentId(int $id): ?int
    {
        $parents = PeopleEloquentModel::find($id)->parents()->get();
        if ($parents->isEmpty()) {
            return null;
        } else {
            return $parents[0]->parent_id;
        }
    }

    /**
     * @return Collection|PersonShortModel[]
     */
    private function getParentsToggle(int $id): Collection
    {
        $array = [];
        $parents = PeopleEloquentModel::select("id", "surname", "name", "patronymic")
            ->find($id)
            ->parentsPerson()
            ->get();
        foreach ($parents as $parent) {
            $array[] = new PersonShortModel(
                $parent->id,
                $parent->surname,
                $parent->name,
                $parent->patronymic
            );
        }
        return collect($array);
    }

    private function getPersonShort(int $id): PersonShortModel
    {
        $person = PeopleEloquentModel::select("id", "surname", "name", "patronymic")->find($id);
        return new PersonShortModel(
            $person->id,
            $person->surname,
            $person->name,
            $person->patronymic
        );
    }
}
