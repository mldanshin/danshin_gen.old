<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginScreenCanBeRendered(): void
    {
        $response = $this->get(route("login"));

        $response->assertStatus(200);
    }

    /**
     * @dataProvider providerAuthenticateSuccess
     */
    public function testAuthenticateSuccess(string $identifier, string $password): void
    {
        $this->seed();

        $response = $this->post(route("login.handler"), [
            'identifier' => $identifier,
            'password' => $password,
        ]);
        
        $this->assertAuthenticated();
    }

     /**
     * @return array[]
     */
    public function providerAuthenticateSuccess(): array
    {
        return [
            ["mail@danshin.net", "secret1"],
            ["9991112222", "secret1"],
            ["9998882222", "secret1"],
            ["9992222222", "secret2"],
            ["natali@fakemail.ru", "secret2"],
            ["9993332222", "secret3"],
            ["9994442222", "secret4"],
        ];
    }

    /**
     * @dataProvider providerAuthenticateWrong
     */
    public function testAuthenticateWrong(?string $identifier, ?string $password): void
    {
        $this->seed();

        $response = $this->post(route("login.handler"), [
            'identifier' => $identifier,
            'password' => $password,
        ]);

        $this->assertGuest();

        $response->assertRedirect(route("index"));
    }

    /**
     * @return array[]
     */
    public function providerAuthenticateWrong(): array
    {
        return [
            ["1119991112222", "secret1"],
            [null, "secret1"],
            ["1119991112222", null],
            [null, null],
            ["user", "secret1"],
            ["9991112222", "secret"],
            ["user1", "password"],
            ["user@blabla.ru", "password"],
            ["mail23@danshin.net", "secret1"],
            ["9991112222", ""],
            ["", "secret1"],
        ];
    }
}
