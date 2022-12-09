<?php

namespace Tests\Feature\Repositories\Person\Editable\Form;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\MarriageRole as MarriageRoleEloquentModel;
use App\Models\Eloquent\ParentRole as ParentRoleEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Editable\Form\Marriage as MarriageModel;
use App\Models\Person\Editable\Form\ParentModel as ParentModel;
use App\Models\Person\Editable\Form\Person as PersonModel;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\People\Ordering\Name as NameOrdering;
use App\Repositories\Person\Editable\Form\Person as PersonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PersonTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;

    public function testCreate(): PersonRepository
    {
        $repository = new PersonRepository(
            new PersonShortRepository(),
            new NameOrdering()
        );
        $this->assertInstanceOf(PersonRepository::class, $repository);
        return $repository;
    }

    public function testGetById(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();
        
        $peopleId = PeopleEloquentModel::limit(5)->pluck("id")->random();

        $repository = new PersonRepository(
            new PersonShortRepository(),
            new NameOrdering()
        );
        $model = $repository->getById($peopleId);
        $this->assertInstanceOf(PersonModel::class, $model);
    }

    /**
     * @depends testCreate
     */
    public function testGetEmpty(PersonRepository $repository): void
    {
        $this->assertInstanceOf(PersonModel::class, $repository->getEmpty());
    }

    /**
     * @depends testCreate
     */
    public function testGetParentEmpty(PersonRepository $repository): void
    {
        $this->seed();

        $personId = PeopleEloquentModel::pluck("id");
        $roleParent = ParentRoleEloquentModel::pluck("id");
        for ($i = 0; $i < 5; $i++) {
            $this->assertInstanceOf(ParentModel::class, $repository->getParentEmpty(
                $personId->random(),
                $roleParent->random()
            ));
        }
    }

    /**
     * @depends testCreate
     */
    public function testGetMarriageEmpty(PersonRepository $repository): void
    {
        $this->seed();

        $personId = PeopleEloquentModel::pluck("id");
        $genderId = GenderEloquentModel::pluck("id");
        $roleMarriage = MarriageRoleEloquentModel::pluck("id");

        for ($i = 0; $i < 5; $i++) {
            $this->assertInstanceOf(MarriageModel::class, $repository->getMarriageEmpty(
                $personId->random(),
                $genderId->random(),
                $roleMarriage->random()
            ));
        }
    }
}
