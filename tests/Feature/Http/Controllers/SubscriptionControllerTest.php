<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerCreateSuccess
     */
    public function testCreateSuccess(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("events.subscription.create"));
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
            ->get(route("events.subscription.create"));
        $response->assertRedirect(route("events.subscription.edit"));
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
     * @dataProvider providerEditSuccess
     */
    public function testEditSuccess(int $userId): void
    {
        $this->seed();

        $user = User::find($userId);

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get(route("events.subscription.edit"));
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
            ->get(route("events.subscription.edit"));
        $response->assertRedirect(route("events.subscription.create"));
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
}
