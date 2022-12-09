<?php

namespace Tests\Feature\Http\Controllers\Events;

use App\Models\Eloquent\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\DataProvider\User as UserDataProvider;

final class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider providerCreateSuccess
     */
    public function testCreateSuccess(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.events.subscription.create"));
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerCreateSuccess(): array
    {
        return [
            [3],
            [4]
        ];
    }

    /**
     * @dataProvider providerCreateWrong
     */
    public function testCreateWrong(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.events.subscription.create"));
        $response->assertRedirect(route("partials.events.subscription.edit"));
    }

    /**
     * @return array[]
     */
    public function providerCreateWrong(): array
    {
        return [
            [1],
            [2]
        ];
    }

    /**
     * @dataProvider providerStoreSuccess
     */
    public function testStoreSuccess(int $userId): void
    {
        $this->seed();
        config(["services.telegram-bot-api.waiting_time" => 2]);

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post(route("partials.events.subscription.store"),[
                "user_id" => (string)$user->id,
                "code" => uniqid()
            ]);
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerStoreSuccess(): array
    {
        return [
            [3],
            [4]
        ];
    }

    /**
     * @dataProvider providerStoreWrong
     */
    public function testStoreWrong(?int $userId, ?string $code): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post(route("partials.events.subscription.store"),[
                "user_id" => (string)$userId,
                "code" => $code
            ]);
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerStoreWrong(): array
    {
        return [
            [3, null], //code is missing
            [4, ""], //code is missing
        ];
    }

    /**
     * @dataProvider providerStoreWrongUser
     */
    public function testStoreWrongUser(?int $userId, ?string $code): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.events.subscription.store"),[
                "user_id" => (string)$userId,
                "code" => $code
            ]);
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerStoreWrongUser(): array
    {
        return [
            [null, "1234"], //user is missing
            [null, null], //user and code is missing
            [111, "123"], //invalid user
            [1, "123"], //not unique user
        ];
    }

    /**
     * @dataProvider providerEditSuccess
     */
    public function testEditSuccess(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.events.subscription.edit"));
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerEditSuccess(): array
    {
        return [
            [1],
            [2]
        ];
    }

    /**
     * @dataProvider providerEditWrong
     */
    public function testEditWrong(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("partials.events.subscription.edit"));
        $response->assertRedirect(route("partials.events.subscription.create"));
    }

    /**
     * @return array[]
     */
    public function providerEditWrong(): array
    {
        return [
            [3],
            [4]
        ];
    }

    /**
     * @dataProvider providerDeleteSuccess
     */
    public function testDeleteSuccess(int $userId): void
    {
        $this->seed();

        $response = $this->actingAs(User::find($userId))
            ->withSession(['banned' => false])
            ->post(route("partials.events.subscription.delete"),[
                "user_id" => (string)$userId
            ]);
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerDeleteSuccess(): array
    {
        return [
            [1],
            [2]
        ];
    }

    /**
     * @dataProvider providerDeleteWrong
     */
    public function testDeleteWrong(int $userId, int $userFakeId): void
    {
        $this->seed();

        $response = $this->actingAs(User::find($userId))
            ->withSession(['banned' => false])
            ->post(route("partials.events.subscription.delete"),[
                "user_id" => (string)$userFakeId
            ]);
        $response->assertRedirect(route("partials.events.subscription.edit"));
    }

    /**
     * @return array[]
     */
    public function providerDeleteWrong(): array
    {
        return [
            [1, 2],
            [3, 3],
            [4, 1]
        ];
    }
}
