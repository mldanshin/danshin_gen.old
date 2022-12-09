<?php

namespace Tests\Feature\Http;

use App\Exceptions\NotFoundException;
use App\Http\Validate;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\TestCase;

final class ValidateTest extends TestCase
{
    use PeopleDataProvider;
    use RefreshDatabase;

    public function testPersonIdSucess(): void
    {
        $this->seed();

        $peopleId = PeopleEloquentModel::limit(10)->pluck("id");
        foreach ($peopleId as $personId) {
            $this->assertTrue(Validate::personId($personId));
        }
    }

    public function testPersonIdWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();
        foreach ($idWrong as $id) {
            try {
                Validate::personId($id);
            } catch (\Exception $e) {
                $this->assertInstanceOf(NotFoundException::class, $e);
            }
        }
    }

    public function testParentSucess(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $this->assertTrue(Validate::parent($person->id, $this->randomParent($person)));
                $this->assertTrue(Validate::parent($person->id, null));
        }
    }

    public function testParentWrong(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();

        foreach ($people as $person) {
            
            try {
                $wrongParent = $this->randomExceptParent($person->id);
                Validate::parent($person->id, $wrongParent);
            } catch (\Exception $e) {
                $this->assertInstanceOf(NotFoundException::class, $e);
            }

            try {
                $idWrong = $this->peopleIdWrong();
                foreach ($idWrong as $id) {
                    Validate::parent($person->id, $id);
                }
            } catch (\Exception $e) {
                $this->assertInstanceOf(NotFoundException::class, $e);
            }
        }
    }
}
