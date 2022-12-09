<?php

namespace Tests\Feature\Repositories\Person\Readable;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Readable\Person as PersonModel;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\People\Ordering\Age;
use App\Repositories\Person\Readable\Person as PersonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PersonTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;

    public function testCreate(): void
    {
        $repository = new PersonRepository(new PersonShortRepository(), new Age());
        $this->assertInstanceOf(PersonRepository::class, $repository);
    }

    public function testGetByIdSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $peopleId = PeopleEloquentModel::limit(5)->pluck("id")->all();
        foreach ($peopleId as $id) {
            $repository = new PersonRepository(new PersonShortRepository(), new Age());
            $this->assertInstanceOf(PersonModel::class, $repository->getById($id));
        }
    }
}
