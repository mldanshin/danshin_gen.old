<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerShowTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    public function testSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $peopleId = PeopleEloquentModel::limit(10)->pluck("id")->all();
        foreach ($peopleId as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("partials.person.show", $id));
            $response->assertStatus(200);
        }
    }

    public function testWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();

        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("partials.person.show", $id));
            $response->assertStatus(404);
        }
    }
}
