<?php

namespace Tests\Feature\Services\Auth\Registration;

use App\Models\Eloquent\UserUnconfirmed;
use App\Notifications\Auth\Registration\ConfirmationEmail as NotificationEmail;
use App\Notifications\Auth\Registration\ConfirmationSms as NotificationSms;
use App\Services\Auth\Registration\NotificationSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class NotificationSenderTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): NotificationSender
    {
        $model = new NotificationSender();
        $this->assertInstanceOf(NotificationSender::class, $model);
        return $model;
    }

    /**
     * @depends testCreate
     * @dataProvider providerSendEmail
     */
    public function testSendEmail(int $idUser, NotificationSender $object): void
    {
        $this->seed();

        Notification::fake();

        $user = UserUnconfirmed::find($idUser);
        $object->send($user);

        Notification::assertSentTo(
            [$user], NotificationEmail::class
        );
    }

    public function providerSendEmail(): array
    {
        return [
            [5],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerSendSms
     */
    public function testSendSms(int $idUser, NotificationSender $object): void
    {
        $this->seed();

        Notification::fake();

        $user = UserUnconfirmed::find($idUser);
        $object->send($user);

        Notification::assertSentTo(
            [$user], NotificationSms::class
        );
    }

    public function providerSendSms(): array
    {
        return [
            [1],
            [2],
            [3],
            [4],
        ];
    }
}
