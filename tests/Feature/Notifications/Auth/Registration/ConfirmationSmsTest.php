<?php

namespace Tests\Feature\Notifications\Auth\Registration;

use App\Channels\SmsRu\Messages\NexmoMessage;
use App\Models\Eloquent\UserUnconfirmed;
use App\Notifications\Auth\Registration\ConfirmationSms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ConfirmationSmsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerCreate
     */
    public function testCreate(string $code): ConfirmationSms
    {
        $this->seed();

        $user = UserUnconfirmed::get()->random();

        $obj = new ConfirmationSms($code);
        $this->assertInstanceOf(ConfirmationSms::class, $obj);
        $this->assertInstanceOf(NexmoMessage::class, $obj->toNexmo($user));
        $this->assertEquals(
            __("auth.confirm.code") . " {$code}",
            $obj->toNexmo($user)->content
        );
        return $obj;
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
