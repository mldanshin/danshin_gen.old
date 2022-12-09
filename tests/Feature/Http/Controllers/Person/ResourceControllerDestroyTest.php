<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerDestroyTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();
        $user = $this->getAdmim();

        $peopleId = PeopleEloquentModel::pluck("id")->random();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->delete(route("partials.person.destroy", $peopleId));
        $response->assertStatus(200);
        
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.person.show", $peopleId));
        $response->assertStatus(404);

    }

    public function testWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();

        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("partials.person.destroy", $id));
            $response->assertStatus(404);
        }
    }
}
