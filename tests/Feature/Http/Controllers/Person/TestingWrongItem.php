<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use Tests\DataProvider\User as UserDataProvider;

trait TestingWrongItem
{
    use UserDataProvider;

    private function testItem(string $field, mixed $value): void
    {
        $gender = GenderEloquentModel::value("id");
        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.store"), [
                "person_id" => 0,
                "person_gender" => $gender,
                $field => $value
            ]);
        $response->assertStatus(302);
    }
}
