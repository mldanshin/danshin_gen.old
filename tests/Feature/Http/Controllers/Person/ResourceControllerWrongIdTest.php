<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerWrongIdTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    public function testMissing(): void
    {
        $this->seed();

        $gender = GenderEloquentModel::value("id");
        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.store"), [
                "person_gender" => $gender
            ]);
        $response->assertStatus(302);
    }
}
