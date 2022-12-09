<?php

namespace Tests\Feature\Http\Controllers\Events;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\DataProvider\User as UserDataProvider;

final class EventsControllerTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    public function testShowSuccess(): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("partials.events.show"));
        $response->assertStatus(200);
    }
}
