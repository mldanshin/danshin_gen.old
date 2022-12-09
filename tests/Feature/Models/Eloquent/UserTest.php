<?php

namespace Tests\Feature\Models\Eloquent;

use App\Models\Eloquent\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerGetRole
     */
    public function testGetRole(int $userId, int $roleIdExpected): void
    {
        $this->seed();

        $this->assertEquals($roleIdExpected, User::find($userId)->getRole()->role_id);
    }

    /**
     * @return mixed[]
     */
    public function providerGetRole(): array
    {
        return [
            [1, 1],
            [2, 3],
            [3, 2],
            [4, 2]
        ];
    }

    /**
     * @dataProvider providerIsSubscription
     */
    public function testIsSubscription(int $idUser, bool $expected): void
    {
        $this->seed();

        $this->assertEquals($expected, User::find($idUser)->isSubscription());
    }

    /**
     * @return array[]
     */
    public function providerIsSubscription(): array
    {
        return [
            [1, true],
            [2, true],
            [3, false],
            [4, false]
        ];
    }

    /**
     * @dataProvider providerRouteNotificationForTelegram
     */
    public function testRouteNotificationForTelegram(int $idPerson, ?string $expected): void
    {
        $this->seed();

        $this->assertEquals($expected, User::find($idPerson)->routeNotificationForTelegram(new Notification()));
    }

    /**
     * @return array[]
     */
    public function providerRouteNotificationForTelegram(): array
    {
        return [
            [1, "a123"],
            [2, "c984"],
            [3, null],
            [4, null]
        ];
    }
}
