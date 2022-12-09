<?php

namespace Tests\Feature\Http\Controllers\Person;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerCloseTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    public function testSuccess(): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("partials.person.close"));
        $response->assertStatus(200);
    }
}
