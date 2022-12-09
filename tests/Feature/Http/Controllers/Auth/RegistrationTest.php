<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\Eloquent\UserUnconfirmed;
use App\Repositories\Auth\Identifier;
use App\Repositories\Auth\Registration\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationScreenCanBeRendered(): void
    {
        $response = $this->get(route("register"));

        $response->assertStatus(200);
    }

    /**
     * @dataProvider providerHandlerFormSuccess
     */
    public function testHandlerFormSuccess(string $identifier, string $password): void
    {
        $this->seed();
        $registrationUser = new Registration(new Identifier());

        $response = $this->post(route("register.handler"), [
            'identifier' => $identifier,
            'password' => $password,
            "password_confirmation" => $password
        ]);

        $response->assertRedirect(
            route("register.confirmation", [UserUnconfirmed::where("identifier", $identifier)->value("id")])
        );
    }

    /**
     * @return array[]
     */
    public function providerHandlerFormSuccess(): array
    {
        return [
            ["9995552222", "password1"],
            ["9996662222", "password2"],
            ["9997772222", "password1"],
            ["9990002222", "password3"],
            ["bilet@fakemail.ru", "password4"],
            ["max@fakemail.ru", "password4"],

            //not unique identifier
            ["8881112222", "password3"],
            ["8883332222", "password3"],
            ["8884442222", "password3"],
        ];
    }

    /**
     * @dataProvider providerHandlerFormWrong
     */
    public function testHandlerFormWrong(?string $identifier, ?string $password, ?string $passwordConfirmation): void
    {
        $this->seed();

        $response = $this->post(route("register.handler"), [
            'identifier' => $identifier,
            'password' => $password,
            "password_confirmation" => $passwordConfirmation
        ]);

        $response->assertRedirect(route("register"));
    }

    /**
     * @return array[]
     */
    public function providerHandlerFormWrong(): array
    {
        return [
            ["abcdef", "password1", "password1"], //invalid identifier
            ["abcdef@blabla", "password1", "password1"], //invalid identifier
            ["123phone", "password1", "password1"], //invalid identifier
            ["@blabla.ru", "password1", "password1"], //invalid identifier
            ["abcdef@", "password1", "password1"], //invalid identifier
            [null, "password1", "password1"], //invalid identifier
            ["99955522229", "password1", "word1"], //invalid count phone
            ["99955", "password1", "word1"], //invalid count phone
            ["9995552222", "password1", "word1"], //error password confirmation
            ["9995552222", "password1", null], //not password
            ["9995552222", null, null], //not password
            ["9991112222", "password2", "password2"], //exist user
            ["9994442222", "password2", "password2"], //exist user
        ];
    }

    /**
     * @dataProvider providerCreateConfirmationSuccess
     */
    public function testCreateConfirmationSuccess(int $userId): void
    {
        $this->seed();

        $response = $this->get(route("register.confirmation", [$userId]));
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerCreateConfirmationSuccess(): array
    {
        return [
            [4],
            [5],
        ];
    }

    /**
     * @dataProvider providerCreateConfirmationWrong
     */
    public function testCreateConfirmationWrong(int $userId): void
    {
        $this->seed();

        $response = $this->get(route("register.confirmation", [$userId]));
        $response->assertRedirect(route("register.confirmation-repeated", [$userId]));
    }

    /**
     * @return array[]
     */
    public function providerCreateConfirmationWrong(): array
    {
        return [
            [1],
            [2],
            [3],
        ];
    }

    public function testCreateRepeatConfirmation(): void
    {
        $this->seed();
        $users = UserUnconfirmed::limit(5)->get();

        foreach ($users as $user) {
            $response = $this->get(route("register.confirmation-repeated", [$user->id]));
            $response->assertStatus(200);
        }
    }

    /**
     * @dataProvider providerRepeatConfirmationSuccess
     */
    public function testRepeatConfirmationSuccess(string $id): void
    {
        $this->seed();

        $response = $this->post(route("register.confirmation-repeated.handler"), [
            'id' => $id
        ]);

        $response->assertRedirect(
            route("register.confirmation", [$id])
        );
    }

    /**
     * @return array[]
     */
    public function providerRepeatConfirmationSuccess(): array
    {
        return [
            ["2"],
            ["3"],
        ];
    }

    /**
     * @dataProvider providerRepeatConfirmationWrong
     */
    public function testRepeatConfirmationWrong(?string $id): void
    {
        $this->seed();

        $response = $this->post(route("register.confirmation-repeated.handler"), [
            'id' => $id
        ]);

        $response->assertRedirect(route("index"));
    }

    /**
     * @return array[]
     */
    public function providerRepeatConfirmationWrong(): array
    {
        return [
            [null], //empty id
            [""], //empty id
            ["-3"], //invalid id
            ["0"], //invalid id
            ["1"], //time has not expired
            ["4"], //time has not expired
            ["5"], //time has not expired
        ];
    }

    public function testConfirmSuccess(): void
    {
        $this->seed();

        $users = $this->providerConfirmSuccess();

        foreach ($users as $user) {
            $response = $this->post(route("register.confirmation.handler"), [
                'id' => (string)$user->id,
                "code" => $user->code
            ]);

            $response->assertRedirect(route("index"));
        }
    }

    /**
     * @return UserUnconfirmed[]
     */
    public function providerConfirmSuccess(): array
    {
        return [
            UserUnconfirmed::find(4),
            UserUnconfirmed::find(5),
        ];
    }

    /**
     * @dataProvider providerConfirmWrong
     */
    public function testConfirmWrong(?string $id, ?string $code): void
    {
        $this->seed();

        $response = $this->post(route("register.confirmation.handler"), [
            'id' => $id,
            "code" => $code
        ]);

        if (empty($id)) {
            $response->assertRedirect(route("index"));
        } else {
            $response->assertRedirect(route("register.confirmation", [$id])); 
        }
    }

    /**
     * @return array[]
     */
    public function providerConfirmWrong(): array
    {
        return [
            [null, null], //empty id and code
            [null, "code"], //empty id, invalid code
            ["", "code"], //empty id, invalid code
            ["-3", "code"], //invalid id, invalid code
            ["0", "code"], //invalid id, invalid code
            ["1", "code"], //attempts are exhausted, invalid code
            ["2", "code"], //time is up, invalid code
            ["3", "code"], //time is up, invalid code
            ["5", null], //empty code
            ["5", "code"], //invalid code
            ["5", "code"], //invalid code, 
        ];
    }

    public function testReduceAttempts(): void
    {
        $this->seed();

        $data = $this->providerReduceAttempts();
        foreach ($data as $item) {
            $this->post(route("register.confirmation.handler"), [
                'id' => $item[0],
                "code" => $item[1]
            ]);
            $this->assertEquals(
                $item[2],
                UserUnconfirmed::find($item[0])->attempts
            );
        }
    }

    /**
     * @return array[]
     */
    private function providerReduceAttempts(): array
    {
        return [
            ["5", null, 2],
            ["5", "code", 1],
            ["4", "code", 0],
        ];
    }
}
