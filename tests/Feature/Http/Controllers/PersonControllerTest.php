<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class PersonControllerTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;
    use WithFaker;
    use UserDataProvider;

    public function testCreateSuccess(): void
    {
        $this->seed();

        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("person.create", $this->filterOrderingDataProvider()));
            $response->assertStatus(200);
        }
    }

    public function testShowSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $people = PeopleEloquentModel::pluck("id");
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(
                    route(
                        "person.show",
                        array_merge($this->filterOrderingDataProvider(), ["id" => $people->random()])
                    )
            );
            $response->assertStatus(200);
        }
    }

    public function testShowWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();

        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("person.show", $id));
            $response->assertStatus(404);
        }
    }

    public function testEditSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $people = PeopleEloquentModel::pluck("id");
        for ($i = 0; $i < 10; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(
                    route(
                        "person.edit",
                        array_merge($this->filterOrderingDataProvider(), ["id" => $people->random()])
                    )
            );
            $response->assertStatus(200);
        }
    }

    public function testEditWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();
        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("person.edit", $id));
            $response->assertStatus(404);
        }
    }
}
