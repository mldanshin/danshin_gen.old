<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class IndexControllerTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    public function testIndex(): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("index"));
        $response->assertStatus(200);
    }
}
