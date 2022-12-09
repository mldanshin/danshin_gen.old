<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class TreeControllerTest extends TestCase
{
    use PeopleDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    public function testShowSuccess(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(
                    route("tree", [
                        "id" => $person->id,
                        "parentId" => $this->randomParent($person)
                    ])
            );
            $response->assertStatus(200);
        }
    }

    public function testShowWrongParent(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $wrongParent = $this->randomExceptParent($person->id);
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(
                    route("tree", [
                        "id" => $person->id,
                        "parentId" => $wrongParent
                    ])
            );
            $response->assertStatus(404);
        }
    }

    public function testShowWrongPerson(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();
        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(
                    route("tree", [
                        "id" => $id,
                        "parentId" => null
                    ])
            );
            $response->assertStatus(404);
        }
    }

    public function testShowImageSuccess(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("tree.image", [$person->id, $this->randomParent($person)]));
            $response->assertStatus(200);
        }
    }

    public function testShowImageWrongParent(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $wrongParent = $this->randomExceptParent($person->id);
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("tree.image", [$person->id, $wrongParent]));
            $response->assertStatus(404);
        }
    }

    public function testShowImageWrongPerson(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();
        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("tree.image", [$id, null]));
            $response->assertStatus(404);
        }
    }
}
