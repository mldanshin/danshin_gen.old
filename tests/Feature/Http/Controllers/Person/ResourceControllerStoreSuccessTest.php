<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerStoreSuccessTest extends TestCase
{
    use FormData;
    use RefreshDatabase;
    use UserDataProvider;

    public function test(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::get();
        $gender = GenderEloquentModel::get();
        $user = $this->getAdmim();

        for ($i = 0; $i < 25; $i++) {
            $response = $this->actingAs($user)
                ->withSession(['banned' => false])
                ->post(
                    route("partials.person.store"),
                    $this->getFormData(0, $people, $gender->random(), null)
                );
            $response->assertStatus(200);
        }
    }
}
