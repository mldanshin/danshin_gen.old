<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class EventControllerTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    public function testShowEvents(): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("events.show"));
        $response->assertStatus(200);
    }
}
