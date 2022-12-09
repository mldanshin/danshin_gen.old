<?php

namespace App\Repositories\Person\Readable;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Readable\Internet as InternetModel;
use App\Models\Person\Readable\Marriage as MarriageModel;
use App\Models\Person\Readable\ParentModel as ParentModel;
use App\Models\Person\Readable\Person as PersonModel;
use App\Models\Person\Readable\Residence as ResidenceModel;
use App\Repositories\PersonShort;
use App\Repositories\People\Ordering\Age;
use App\Repositories\People\Ordering\OrderingContract;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Support\Collection;

final class Person
{
    private Photo $photo;

    public function __construct(
        private PersonShort $personShortRepository,
        private Age $orderByAge
    ) {
        $this->photo = new Photo(PhotoFileSystem::instance());
    }

    public function getById(int|string $id): PersonModel
    {
        $person = PeopleEloquentModel::find($id);

        $children = $person->childrens()->pluck("child_id")->all();

        return new PersonModel(
            $person->id,
            $person->is_unavailable,
            $person->gender_id,
            $person->surname,
            ($person->oldSurname->count() > 0) ? $person->oldSurname()->orderBy("_order")->pluck("surname") : null,
            $person->name,
            $person->patronymic,
            $person->birth_date,
            $person->birth_place,
            $person->death_date,
            $person->burial_place,
            $person->note,
            ($person->activities()->count() > 0) ? collect($person->activities()->pluck("name")->all()) : null,
            ($person->emails()->count() > 0) ? collect($person->emails()->pluck("name")->all()) : null,
            $this->getInternet($person),
            ($person->phones()->count() > 0) ? collect($person->phones()->pluck("name")->all()) : null,
            $this->getResidences($person),
            $this->getParents($person),
            $this->getMarriages($person->id),
            empty($children) ? null : $this->personShortRepository->getCollectionById($children, $this->orderByAge),
            $this->getBrothersSisters($person->id, $this->orderByAge),
            $this->photo->getByPerson($person->id),
            new \DateTime()
        );
    }

    /**
     * @return Collection|InternetModel[]|null
     */
    private function getInternet(PeopleEloquentModel $person): ?Collection
    {
        $array = [];
        $collection = $person->internet()->get();
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $array[] = new InternetModel($item->url, $item->name);
            }
            return collect($array);
        } else {
            return null;
        }
    }

    /**
     * @return Collection|ResidenceModel[]|null
     */
    private function getResidences(PeopleEloquentModel $person): ?Collection
    {
        $array = [];
        $collection = $person->residences()->get();
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $array[] = new ResidenceModel($item->name, $item->date_info);
            }
            return collect($array);
        } else {
            return null;
        }
    }

    /**
     * @return Collection|ParentModel[]|null
     */
    private function getParents(PeopleEloquentModel $person): ?Collection
    {
        $array = [];

        $collection = $person->parents()->get();
        $collection->each(
            function ($item) use (&$array) {
                $array[] = new ParentModel(
                    $this->personShortRepository->getPersonById($item->parent_id),
                    $item->parent_role_id
                );
            }
        );

        return empty($array) ? null : collect($array);
    }

    /**
     * @return Collection|MarriageModel[]|null
     */
    private function getMarriages(int $personId): ?Collection
    {
        $array = [];

        $roleScope = MarriageRoleScopeEloquentModel::get();

        $collection1 = MarriageEloquentModel::where("person1_id", $personId)->get();
        $collection1->each(
            function ($item) use (&$array, $roleScope) {
                $array[] = $this->getMarriage(
                    $item->person2_id,
                    $roleScope->find($item->role_scope_id)->role2_id
                );
            }
        );

        $collection2 = MarriageEloquentModel::where("person2_id", $personId)->get();
        $collection2->each(
            function ($item) use (&$array, $roleScope) {
                $array[] = $this->getMarriage(
                    $item->person1_id,
                    $roleScope->find($item->role_scope_id)->role1_id
                );
            }
        );

        return empty($array) ? null : collect($array);
    }

    private function getMarriage(int $person, int $type): MarriageModel
    {
        return new MarriageModel(
            $this->personShortRepository->getPersonById($person),
            $type
        );
    }

    /**
     * @return Collection|PersonShortModel[]|null
     */
    private function getBrothersSisters(int $personId, OrderingContract $ordering): ?Collection
    {
        $array = [];

        $funcParent = function ($query) use ($personId) {
            $query->select("parent_id")->from("parent_child")->where("child_id", $personId);
        };

        $array = ParentChildEloquentModel::whereIn("parent_id", $funcParent)
            ->where("child_id", "<>", $personId)
            ->pluck("child_id")
            ->all();

        return empty($array) ? null : $this->personShortRepository->getCollectionById($array, $ordering);
    }
}
