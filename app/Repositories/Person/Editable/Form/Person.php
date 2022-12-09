<?php

namespace App\Repositories\Person\Editable\Form;

use App\Helpers\Person as PersonHelper;
use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\MarriageRole as MarriageRoleEloquentModel;
use App\Models\Eloquent\ParentRole as ParentRoleEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Pair as PairModel;
use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editable\Internet as InternetModel;
use App\Models\Person\Editable\Residence as ResidenceModel;
use App\Models\Person\Editable\Form\Gender as GenderModel;
use App\Models\Person\Editable\Form\Marriages as MarriagesModel;
use App\Models\Person\Editable\Form\Marriage as MarriageModel;
use App\Models\Person\Editable\Form\Parents as ParentsModel;
use App\Models\Person\Editable\Form\ParentModel as ParentModel;
use App\Models\Person\Editable\Form\Person as PersonModel;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\Person\PhotoFileSystem;
use App\Repositories\People\Ordering\Name as NameOrdering;
use Illuminate\Support\Collection;

final class Person
{
    private Photo $photo;

    public function __construct(
        private PersonShortRepository $personShortRepository,
        private NameOrdering $nameOrdering
    ) {
        $this->photo = new Photo(PhotoFileSystem::instance());
    }

    public function getById(int|string $id): PersonModel
    {
        $person = PeopleEloquentModel::find($id);

        return new PersonModel(
            $person->id,
            $person->is_unavailable,
            $this->getGender($person),
            $person->surname,
            $this->getOldSurname($person),
            $person->name,
            $person->patronymic,
            $person->birth_date,
            $person->birth_place,
            $person->death_date,
            ($person->burial_place === null) ? "" : $person->burial_place,
            ($person->note === null) ? "" : $person->note,
            $person->activities()->pluck("name"),
            $person->emails()->pluck("name"),
            $this->getInternet($person),
            $person->phones()->pluck("name"),
            $this->getResidences($person),
            $this->getParents($person),
            $this->getMarriages($person),
            $this->photo->getByPerson($person->id)
        );
    }

    public function getEmpty(): PersonModel
    {
        return new PersonModel(
            0,
            false,
            $this->getGender(null),
            "",
            collect(),
            "",
            "",
            "",
            "",
            null,
            "",
            "",
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            $this->getParentsEmpty(),
            $this->getMarriagesEmpty(),
            collect(),
        );
    }

    public function getParentEmpty(int $id, int $roleParent): ParentModel
    {
        return new ParentModel(
            0,
            $this->personShortRepository->getCollectionPossibleParents(
                $id,
                $roleParent,
                $this->nameOrdering
            ),
            $roleParent,
            $this->getParentRolePair()
        );
    }

    public function getMarriageEmpty(int $id, int $gender, int $roleSoulmate): MarriageModel
    {
        return new MarriageModel(
            0,
            $this->getMarriageRolePairByGender($gender),
            0,
            $this->personShortRepository->getCollectionPossibleMarriages(
                $id,
                $roleSoulmate,
                $this->nameOrdering
            ),
            $roleSoulmate,
            $this->getMarriageRolePair()
        );
    }

    private function getGender(?PeopleEloquentModel $person): GenderModel
    {
        if ($person === null) {
            $personId = 0;
        } else {
            $personId = $person->gender_id;
        }

        return new GenderModel(
            $this->getGenderPair(),
            $personId
        );
    }

    /**
     * @return Collection|PairModel[]
     */
    private function getGenderPair(): Collection
    {
        $idCollection = GenderEloquentModel::orderBy("id")->pluck("id");
        $array = [];
        foreach ($idCollection as $item) {
            $array[] = new PairModel($item, PersonHelper::gender($item));
        }
        return collect($array);
    }

    /**
     * @return Collection|OldSurnameModel[]
     */
    private function getOldSurname(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->oldSurname()->orderBy("_order")->get();
        foreach ($collection as $item) {
            $array[] = new OldSurnameModel($item->surname, $item->_order);
        }
        return collect($array);
    }

    /**
     * @return Collection|InternetModel[]
     */
    private function getInternet(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->internet()->get();
        foreach ($collection as $item) {
            $array[] = new InternetModel($item->url, $item->name);
        }
        return collect($array);
    }

    /**
     * @return Collection|ResidenceModel[]
     */
    private function getResidences(PeopleEloquentModel $person): Collection
    {
        $array = [];
        $collection = $person->residences()->get();
        foreach ($collection as $item) {
            $array[] = new ResidenceModel($item->name, $item->date_info);
        }
        return collect($array);
    }

    private function getParents(PeopleEloquentModel $person): ParentsModel
    {
        $array = [];

        $collection = $person->parents()->get();
        $collectionPair = $this->getParentRolePair();

        foreach ($collection as $item) {
            $array[] = new ParentModel(
                $item->parent_id,
                $this->personShortRepository->getCollectionPossibleParents(
                    $person->id,
                    $item->parent_role_id,
                    $this->nameOrdering
                ),
                $item->parent_role_id,
                $collectionPair
            );
        }

        return new ParentsModel(
            $collectionPair,
            collect($array)
        );
    }

    private function getParentsEmpty(): ParentsModel
    {
        return new ParentsModel(
            $this->getParentRolePair(),
            collect()
        );
    }

    /**
     * @return Collection|PairModel[]
     */
    private function getParentRolePair(): Collection
    {
        $idCollection = ParentRoleEloquentModel::orderBy("id")->pluck("id");
        $array = [];
        foreach ($idCollection as $item) {
            $array[] = new PairModel($item, PersonHelper::parent($item));
        }
        return collect($array);
    }

    private function getMarriages(PeopleEloquentModel $person): MarriagesModel
    {
        $array = [];

        $roleScope = MarriageRoleScopeEloquentModel::get();

        $roleCurrentOptions = $this->getMarriageRolePairByGender($person->gender_id);
        $roleSoulmateOptions = $this->getMarriageRolePair();

        //selection by the first column
        $collection = MarriageEloquentModel::where("person1_id", $person->id)->get();
        foreach ($collection as $item) {
            $roleCurrent = $roleScope->find($item->role_scope_id)->role1_id;
            $roleSoulmate = $roleScope->find($item->role_scope_id)->role2_id;
            $array[] = new MarriageModel(
                $roleCurrent,
                $roleCurrentOptions,
                $item->person2_id,
                $this->personShortRepository->getCollectionPossibleMarriages(
                    $person->id,
                    $roleSoulmate,
                    $this->nameOrdering
                ),
                $roleSoulmate,
                $roleSoulmateOptions
            );
        }

        //selection by the second column
        $collection = MarriageEloquentModel::where("person2_id", $person->id)->get();
        foreach ($collection as $item) {
            $roleCurrent = $roleScope->find($item->role_scope_id)->role2_id;
            $roleSoulmate = $roleScope->find($item->role_scope_id)->role1_id;
            $array[] = new MarriageModel(
                $roleCurrent,
                $roleCurrentOptions,
                $item->person1_id,
                $this->personShortRepository->getCollectionPossibleMarriages(
                    $person->id,
                    $roleSoulmate,
                    $this->nameOrdering
                ),
                $roleSoulmate,
                $roleSoulmateOptions
            );
        }

        return new MarriagesModel(
            $roleSoulmateOptions,
            collect($array)
        );
    }

    private function getMarriagesEmpty(): MarriagesModel
    {
        return new MarriagesModel(
            $this->getMarriageRolePair(),
            collect()
        );
    }

    /**
     * @return Collection|PairModel[]
     */
    private function getMarriageRolePair(): Collection
    {
        $idCollection = MarriageRoleEloquentModel::orderBy("id")->pluck("id");
        $array = [];
        foreach ($idCollection as $item) {
            $array[] = new PairModel($item, PersonHelper::marriage($item));
        }
        return collect($array);
    }

    /**
     * @return Collection|PairModel[]
     */
    private function getMarriageRolePairByGender(int $gender): Collection
    {
        $collection = GenderEloquentModel::find($gender)->marriages()->orderBy("id")->get();
        $array = [];
        foreach ($collection as $item) {
            $array[] = new PairModel($item->id, PersonHelper::marriage($item->id));
        }
        return collect($array);
    }
}
