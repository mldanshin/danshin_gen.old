<?php

namespace Tests\Feature\Repositories;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Eloquent\MarriageRole as MarriageRoleEloquentModel;
use App\Models\Eloquent\ParentRole as ParentRoleEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\PersonShort;
use App\Repositories\People\Ordering\Name as OrderingName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\TestCase;

final class PersonShortTest extends TestCase
{
    use PeopleDataProvider;
    use RefreshDatabase;

    public function testCreate(): PersonShort
    {
        $repository = new PersonShort();
        $this->assertInstanceOf(PersonShort::class, $repository);
        return $repository;
    }

    /**
     * @depends testCreate
     * @dataProvider getCollectionSelectSuccessProvider
     */
    public function testGetCollectionSelectSuccess(
        string $search,
        int $expected,
        PersonShort $repository
    ): void {
        $this->seed();

        $collection = $repository->getCollection($search, new OrderingName());
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount($expected, $collection->all());
    }

    public function getCollectionSelectSuccessProvider(): array
    {
        return [
            ["Danshin", 8],
            ["Burkina", 1],
            ["Denis", 2],
        ];
    }

    /**
     * @depends testCreate
     */
    public function testGetCollectionSelectSqlInjection(PersonShort $repository): void
    {
        $this->seed();

        $search = " '%a%' OR surname LIKE '%b%' ";
        $collection = $repository->getCollection($search, new OrderingName());
        $this->assertCount(3, $collection->all());
    }

    /**
     * @depends testCreate
     * @dataProvider getCollectionFullProvider
     */
    public function testGetCollectionFull($search, $order, PersonShort $repository): void
    {
        $this->seed();

        $expectedCount = PeopleEloquentModel::count();
        $collection = $repository->getCollection($search, $order);
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount($expectedCount, $collection->all());
    }

    public function getCollectionFullProvider(): array
    {
        return [
            ["", new OrderingName()],
            [null, new OrderingName()],
        ];
    }

    /**
     * @depends testCreate
     */
    public function testGetCollectionById(PersonShort $repository)
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(5)->pluck("id")->all();
        $this->assertInstanceOf(Collection::class, $repository->getCollectionById(
            $people,
            new OrderingName()
        ));
    }

    /**
     * @depends testCreate
     */
    public function testGetCollectionPossibleParents(PersonShort $repository): void
    {
        $this->seed();

        $people = PeopleEloquentModel::pluck("id");
        $parentRoles = ParentRoleEloquentModel::pluck("id");
        $ordering = new OrderingName();

        for ($i = 0; $i < 5; $i++) {
            $this->assertInstanceOf(Collection::class, $repository->getCollectionPossibleParents(
                $people->random(),
                $parentRoles->random(),
                $ordering
            ));
        }
    }

    /**
     * @depends testCreate
     */
    public function testGetCollectionPossibleMarriages(PersonShort $repository): void
    {
        $this->seed();

        $people = PeopleEloquentModel::pluck("id");
        $marriageRoles = MarriageRoleEloquentModel::pluck("id");
        $ordering = new OrderingName();

        for ($i = 0; $i < 5; $i++) {
            $this->assertInstanceOf(Collection::class, $repository->getCollectionPossibleMarriages(
                $people->random(),
                $marriageRoles->random(),
                $ordering
            ));
        }
    }

    /**
     * @depends testCreate
     */
    public function testGetPersonById(PersonShort $repository): void
    {
        $this->seed();

        $peopleId = PeopleEloquentModel::limit(5)->pluck("id")->all();
        foreach ($peopleId as $id) {
            $this->assertInstanceOf(PersonShortModel::class, $repository->getPersonById($id));
        }
    }
}
