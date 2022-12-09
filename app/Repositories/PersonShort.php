<?php

namespace App\Repositories;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleGender as MarriageRoleGenderEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\ParentRoleGender as ParentRoleGenderEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\People\Ordering\OrderingContract;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class PersonShort
{
    /**
     * @return Collection|PersonShortModel[]
     */
    public function getCollection(?string $search, OrderingContract $order): Collection
    {
        $people = $this->getArrayPersonShortModel($this->search($search));

        $order->sort($people);

        return collect($people);
    }

    /**
     * @return Collection|PersonShortModel[]
     */
    public function getCollectionPossibleParents(int $id, int $roleParent, OrderingContract $order): Collection
    {
        $chldrensId = ParentChildEloquentModel::where("parent_id", $id)->pluck("child_id")->all();

        $mariages1Id = MarriageEloquentModel::where("person1_id", $id)->pluck("person2_id")->all();
        $mariages2Id = MarriageEloquentModel::where("person2_id", $id)->pluck("person1_id")->all();
        $mariagesId = array_merge($mariages1Id, $mariages2Id);

        $people = PeopleEloquentModel::select("id", "surname", "name", "patronymic", "birth_date")
            ->where("id", "<>", $id)
            ->whereNotIn("id", $chldrensId)
            ->whereNotIn("id", $mariagesId)
            ->whereIn("gender_id", ParentRoleGenderEloquentModel::where("parent_id", $roleParent)->pluck("gender_id"))
            ->get();

        $people = $this->getArrayPersonShortModel($people);

        $order->sort($people);

        return collect($people);
    }

    /**
     * @return Collection|PersonShortModel[]
     */
    public function getCollectionPossibleMarriages(int $id, int $roleMarriage, OrderingContract $order): Collection
    {
        $chldrensId = ParentChildEloquentModel::where("parent_id", $id)->pluck("child_id")->all();
        $parentsId = ParentChildEloquentModel::where("child_id", $id)->pluck("parent_id")->all();

        $people = PeopleEloquentModel::select("id", "surname", "name", "patronymic", "birth_date")
            ->where("id", "<>", $id)
            ->whereNotIn("id", $chldrensId)
            ->whereNotIn("id", $parentsId)
            ->whereIn("gender_id", MarriageRoleGenderEloquentModel::where("role_id", $roleMarriage)->pluck("gender_id"))
            ->get();

        $people = $this->getArrayPersonShortModel($people);

        $order->sort($people);

        return collect($people);
    }

    /**
     * @param array|int[] $id
     * @return Collection|PersonShortModel[]
     */
    public function getCollectionById(array $id, OrderingContract $order): Collection
    {
        $people = PeopleEloquentModel::whereIn("id", $id)->get();

        $array = [];
        foreach ($people as $person) {
            $array[] = $this->getPerson($person);
        }

        $order->sort($array);

        return collect($array);
    }

    public function getPersonById(int $id): PersonShortModel
    {
        $person = PeopleEloquentModel::find($id);
        return $this->getPerson($person);
    }

    private function getPerson(PeopleEloquentModel $person): PersonShortModel
    {
        return new PersonShortModel(
            $person->id,
            $person->surname,
            ($person->oldSurname()->count() > 0) ? $person->oldSurname()->orderBy("_order")->pluck("surname") : null,
            $person->name,
            $person->patronymic,
            $person->birth_date,
        );
    }

    /**
     * @param  EloquentCollection|PeopleEloquentModel[] $collection
     * @return array|PersonShortModel[]
     */
    private function getArrayPersonShortModel(EloquentCollection $collection): array
    {
        $array = [];
        foreach ($collection as $person) {
            $array[] = $this->getPerson($person);
        }
        return $array;
    }

    /**
     * @return EloquentCollection|PeopleEloquentModel[]
     */
    private function search(?string $searchText): EloquentCollection
    {
        $query = PeopleEloquentModel::select("id", "surname", "name", "patronymic", "birth_date");

        if (!empty($searchText)) {
            $array = explode(" ", $searchText);
            switch (count($array)) {
                case 1:
                    $item = $array[0];
                    $query = $query->where("surname", "LIKE", "%$item%")
                        ->orWhere("name", "LIKE", "%$item%")
                        ->orWhere("patronymic", "LIKE", "%$item%");
                    break;
                case 2:
                    $item1 = $array[0];
                    $item2 = $array[1];
                    $query = $query->where("surname", "LIKE", "%$item1%")
                        ->where("name", "LIKE", "%$item2%");
                    break;
                case 3:
                    $item1 = $array[0];
                    $item2 = $array[1];
                    $item3 = $array[2];
                    $query = $query->where("surname", "LIKE", "%$item1%")
                        ->where("name", "LIKE", "%$item2%")
                        ->where("patronymic", "LIKE", "%$item3%");
                    break;
                default:
                    $item = $searchText;
                    $query = $query->where("surname", "LIKE", "%$item%")
                        ->orWhere("name", "LIKE", "%$item%")
                        ->orWhere("patronymic", "LIKE", "%$item%");
                    break;
            }

            
        }

        $collection = $query->get();

        if (!empty($searchText)) {
            $collection = $collection->merge($this->searchInOldSurname($searchText));
            $collection = $collection->unique();
        }

        return $collection;
    }

    /**
     * @return EloquentCollection|PeopleEloquentModel[]
     */
    private function searchInOldSurname(string $searchText): EloquentCollection
    {
        $searchText = explode(" ", $searchText)[0];
        $peopleId = OldSurnameEloquentModel::where("surname", "LIKE", "%$searchText%")
            ->pluck("person_id");
        return PeopleEloquentModel::select("id", "surname", "name", "patronymic", "birth_date")
            ->whereIn("id", $peopleId)
            ->get();
    }
}
