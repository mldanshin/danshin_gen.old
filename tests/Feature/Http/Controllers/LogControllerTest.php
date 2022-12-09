<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class LogControllerTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider providerInvoke
     */
    public function testInvoke(string $message): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("log"), ["message" => $message]);
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerInvoke(): array
    {
        return [
            ["Error"],
            ["Exception message"]
        ];
    }
}
