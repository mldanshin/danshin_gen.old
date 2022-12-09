<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthWrongTest extends TestCase
{
    use RefreshDatabase;

    public function test(): void
    {
        $this->seed();

        $response = $this->get(route("index"));
        $response->assertStatus(302);

        $response = $this->get(route("events.show"));
        $response->assertStatus(302);

        $response = $this->get(route("download.people", ["pdf"]));
        $response->assertStatus(302);
    }
}
