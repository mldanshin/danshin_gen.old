<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Auth as AuthHelper;
use App\Services\NotificationTypes;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * @dataProvider providerGetConfirmationInfoSender
     */
    public function testGetConfirmationInfoSender(
        NotificationTypes $type,
        string $address,
        callable $callbackExpected
    ): void
    {
        $this->assertEquals(
            $callbackExpected(),
            AuthHelper::getConfirmationInfoSender($type, $address)
        );
    }

    /**
     * @return array[]
     */
    private function providerGetConfirmationInfoSender(): array
    {
        return [
            [
                NotificationTypes::EMAIL,
                "mail@danshin.net",
                fn() => __("auth.confirm.email", ["address" => "mail@danshin.net"])
            ],
            [
                NotificationTypes::PHONE,
                "9996662211",
                fn() => __("auth.confirm.phone", ["address" => "9996662211"])
            ],
            [
                NotificationTypes::EMAIL,
                "",
                fn() => __("auth.confirm.email", ["address" => ""])
            ],
        ];
    }
}
