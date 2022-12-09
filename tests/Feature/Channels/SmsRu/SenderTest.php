<?php

namespace Tests\Feature\Channels\SmsRu;

use App\Channels\SmsRu\Sender;
use App\Models\Eloquent\UserUnconfirmed;
use App\Notifications\Auth\Registration\ConfirmationSms as Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SenderTest extends TestCase
{
    use RefreshDatabase;

    public function test(): void
    {
        $this->seed();

        $obj = new Sender();
        $this->assertInstanceOf(Sender::class, $obj);

        $user = UserUnconfirmed::get()->random();
        $obj->send($user, new Notification("234"));
    }
}
