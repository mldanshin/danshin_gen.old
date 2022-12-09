<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerUpdateSuccessTest extends TestCase
{
    use FormData;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @throws \Exception
     */
    public function test(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();
        $user = $this->getAdmim();

        //preparation
        $people = PeopleEloquentModel::get();
        $person = $people->random();
        $personId = $person->id;
        $pathRelative = $this->createTempPhotoFile();

        //testing
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->put(
                route("partials.person.update", $personId),
                $this->getFormData($personId, $people, $person->gender()->first(), $pathRelative)
            );

        $response->assertStatus(200);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.person.show", $personId));
        $response->assertStatus(200);
    }
}
