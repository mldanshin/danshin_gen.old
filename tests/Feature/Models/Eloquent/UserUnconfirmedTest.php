<?php

namespace Tests\Feature\Models\Eloquent;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\UserUnconfirmed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

final class UserUnconfirmedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerRouteNotificationForMail
     */
    public function testRouteNotificationForMail(
        UserIdentifierType $identifierId,
        string $identifier,
        ?string $expected
    ): void {
        $this->seed();

        $user = new UserUnconfirmed();
        $user->identifier_id = $identifierId->value;
        $user->identifier = $identifier;

        $this->assertEquals($expected, $user->routeNotificationForMail(new Notification()));
    }

    /**
     * @return array[]
     */
    public function providerRouteNotificationForMail(): array
    {
        return [
            [UserIdentifierType::PHONE, "9991112233", null],
            [UserIdentifierType::PHONE, "8887770011", null],
            [UserIdentifierType::EMAIL, "mail@danshin.net", "mail@danshin.net"],
            [UserIdentifierType::EMAIL, "oleg@danshin.net", "oleg@danshin.net"]
        ];
    }

    /**
     * @dataProvider providerRouteNotificationForSms
     */
    public function testRouteNotificationForSms(
        UserIdentifierType $identifierId,
        string $identifier,
        ?string $expected
    ): void {
        $this->seed();

        $user = new UserUnconfirmed();
        $user->identifier_id = $identifierId->value;
        $user->identifier = $identifier;

        $this->assertEquals($expected, $user->routeNotificationForSms(new Notification()));
    }

    /**
     * @return array[]
     */
    public function providerRouteNotificationForSms(): array
    {
        return [
            [UserIdentifierType::PHONE, "9991112233", "9991112233"],
            [UserIdentifierType::PHONE, "8887770011", "8887770011"],
            [UserIdentifierType::EMAIL, "mail@danshin.net", null],
            [UserIdentifierType::EMAIL, "oleg@danshin.net", null]
        ];
    }

    /**
     * @dataProvider providerGetIdentifierType
     */
    public function testGetIdentifierType(int $id, UserIdentifierType $expected): void
    {
        $this->seed();

        $user = UserUnconfirmed::find($id);
        $this->assertEquals($expected, $user->getIdentifierType());
    }

    /**
     * @return array[]
     */
    public function providerGetIdentifierType(): array
    {
        return [
            [1, UserIdentifierType::PHONE],
            [2, UserIdentifierType::PHONE],
            [3, UserIdentifierType::PHONE],
            [5, UserIdentifierType::EMAIL],
        ];
    }
}
