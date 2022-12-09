<?php

namespace Tests\Feature\Notifications\Auth\Registration;

use App\Models\Eloquent\UserUnconfirmed;
use App\Notifications\Auth\Registration\ConfirmationEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

final class ConfirmationEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerCreate
     */
    public function testCreate(string $code): void
    {
        $this->seed();

        $user = UserUnconfirmed::get()->random();

        $obj = new ConfirmationEmail($code);
        $this->assertInstanceOf(ConfirmationEmail::class, $obj);
        $this->assertInstanceOf(MailMessage::class, $obj->toMail($user));
        $this->assertEquals(
            __("auth.confirm.subject"),
            $obj->toMail($user)->subject
        );
        $this->assertEquals(
            "auth.notification-code-email",
            $obj->toMail($user)->view
        );
        $this->assertEquals(
            ["code" => $code],
            $obj->toMail($user)->viewData
        );
    }

    /**
     * @return array[]
     */
    public function providerCreate(): array
    {
        return [
            ["1234"],
            ["987"],
            ["4567"],
        ];
    }
}
