<?php

namespace App\Repositories\Person\Editable\Request;

use App\Models\Eloquent\Activity as ActivityEloquentModel;
use App\Models\Eloquent\Email as EmailEloquentModel;
use App\Models\Eloquent\Internet as InternetEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Phone as PhoneEloquentModel;
use App\Models\Eloquent\Residence as ResidenceEloquentModel;
use App\Models\Person\Editable\Request\Marriage as MarriageModel;
use App\Models\Person\Editable\Request\Person as PersonModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class Person
{
    private Photo $photo;

    public function __construct()
    {
        $this->photo = new Photo(PhotoFileSystem::instance());
    }

    public function store(PersonModel $requestModel): int
    {
        $eloquentModel = new PeopleEloquentModel();
        return $this->save($eloquentModel, $requestModel);
    }

    public function update(PersonModel $requestModel): int
    {
        $eloquentModel = PeopleEloquentModel::find($requestModel->getId());
        return $this->save($eloquentModel, $requestModel);
    }

    public function delete(int|string $id): void
    {
        $res = DB::transaction(
            function () use ($id) {
                $person = PeopleEloquentModel::where("id", $id);
                $person->delete();
                return true;
            }
        );
        if ($res) {
            $this->photo->delete((int)$id);
        }
    }

    private function save(PeopleEloquentModel $eloquentModel, PersonModel $requestModel): int
    {
        $personId = DB::transaction(
            function () use ($eloquentModel, $requestModel) {
                $eloquentModel->is_unavailable = $requestModel->isUnavailable();
                $eloquentModel->gender_id = $requestModel->getGender();
                $eloquentModel->surname = $requestModel->getSurname();
                $eloquentModel->name = $requestModel->getName();
                $eloquentModel->patronymic = $requestModel->getPatronymic();
                $eloquentModel->birth_date = $requestModel->getBirthDate();
                $eloquentModel->birth_place = $requestModel->getBirthPlace();
                $eloquentModel->death_date = $requestModel->getDeathDate();
                $eloquentModel->burial_place = $requestModel->getBurialPlace();
                $eloquentModel->note = $requestModel->getNote();
                $eloquentModel->save();

                $this->saveItem(
                    $eloquentModel->activities(),
                    $requestModel->getActivities(),
                    fn($item) => new ActivityEloquentModel(
                        [
                        "name" => $item
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->emails(),
                    $requestModel->getEmails(),
                    fn($item) => new EmailEloquentModel(
                        [
                        "name" => $item
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->internet(),
                    $requestModel->getInternet(),
                    fn($item) => new InternetEloquentModel(
                        [
                        "name" => $item->getName(),
                        "url" => $item->getUrl()
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->oldSurname(),
                    $requestModel->getOldSurname(),
                    fn($item) => new OldSurnameEloquentModel(
                        [
                        "surname" => $item->getSurname(),
                        "_order" => $item->getOrder()
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->phones(),
                    $requestModel->getPhones(),
                    fn($item) => new PhoneEloquentModel(
                        [
                        "name" => $item
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->residences(),
                    $requestModel->getResidences(),
                    fn($item) => new ResidenceEloquentModel(
                        [
                        "name" => $item->getName(),
                        "date_info" => $item->getDate()
                        ]
                    )
                );
                $this->saveItem(
                    $eloquentModel->parents(),
                    $requestModel->getParents(),
                    fn($item) => new ParentChildEloquentModel(
                        [
                        "parent_id" => $item->getPerson(),
                        "parent_role_id" => $item->getRole()
                        ]
                    )
                );
                $this->saveMarriage($eloquentModel->id, $requestModel->getMarriages());

                return $eloquentModel->id;
            }
        );

        if ($personId) {
            $this->photo->save($personId, $requestModel->getPhoto());
        }

        return $personId;
    }

    /**
     * @param \Traversable|mixed[]|null $iterator
     */
    private function saveItem(HasMany $roleScope, ?\Traversable $iterator, callable $func): void
    {
        $roleScope->delete();

        if ($iterator !== null) {
            foreach ($iterator as $item) {
                $roleScope->save($func($item));
            }
        }
    }

    /**
     * @param Collection|MarriageModel[]|null $collection
     */
    private function saveMarriage(int $personId, ?Collection $collection): void
    {
        MarriageEloquentModel::where("person1_id", $personId)->delete();
        MarriageEloquentModel::where("person2_id", $personId)->delete();

        if ($collection !== null) {
            foreach ($collection as $item) {
                $roleScope = MarriageRoleScopeEloquentModel::where(
                    [
                    "role1_id" => $item->getRoleCurrent(),
                    "role2_id" => $item->getRoleSoulmate()
                    ]
                )->value("id");
                MarriageEloquentModel::create(
                    [
                    "person1_id" => $personId,
                    "person2_id" => $item->getSoulmate(),
                    "role_scope_id" => $roleScope
                    ]
                );
            }
        }
    }
}
