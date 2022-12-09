<?php

namespace Tests\Feature\Repositories\Events;

use App\Models\Eloquent\SubscriberEvent;
use App\Models\Eloquent\User;
use App\Repositories\Events\Subscription;
use App\Services\Events\TelegramUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        $this->seed();

        $users = User::limit(5)->get();
        foreach ($users as $user) {
            $this->assertInstanceOf(Subscription::class, new Subscription($user));
        }
    }

    public function testGetUser(): void
    {
        $this->seed();

        $users = User::limit(5)->get();
        foreach ($users as $user) {
            $subscription = new Subscription($user);
            $this->assertEquals($user, $subscription->getUser());
        }
    }

    /**
     * @dataProvider providerCreateOrUpdadeTelegram
     */
    public function testCreateOrUpdadeTelegram(
        int $idUser,
        string $telegramId,
        ?string $username,
        string $telegramIdExpected,
        ?string $usernameExpected,
    ): void {
        $this->seed();

        $user = User::find($idUser);
        $telegramUser = new TelegramUser($telegramId, $username);
        $subscription = new Subscription($user);

        $this->assertTrue($subscription->createOrUpdadeTelegram($telegramUser));

        $telegramActual = User::find($idUser)->telegram()->first();
        $this->assertEquals($telegramIdExpected, $telegramActual->telegram_id);
        $this->assertEquals($usernameExpected, $telegramActual->telegram_username);
    }

    /**
     * @return array[]
     */
    public function providerCreateOrUpdadeTelegram(): array
    {
        return [
            [1, "a123", null, "a123", null],
            [2, "c984", "nata234", "c984", "nata234"],
            [3, "bn34", null, "bn34", null],
            [4, "lk5657", "olg56", "lk5657", "olg56"],
        ];
    }

    /**
     * @dataProvider providerCreateSubscriberEventSuccess
     */
    public function testCreateSubscriberEventSuccess(int $idUser): void
    {
        $this->seed();

        $user = User::find($idUser);
        $subscription = new Subscription($user);

        $telegram = $user->telegram()->first();
        $telegramUser = new TelegramUser($telegram->telegram_id, $telegram->username);

        $subscription->createSubscriberEvent($telegramUser);

        $this->assertTrue(SubscriberEvent::where("user_id", $idUser)->exists());
    }

    /**
     * @return array[]
     */
    public function providerCreateSubscriberEventSuccess(): array
    {
        return [
            [5]
        ];
    }

    /**
     * @dataProvider providerCreateSubscriberEventExist
     */
    public function testCreateSubscriberEventExist(int $idUser): void
    {
        $this->seed();

        $user = User::find($idUser);
        $subscription = new Subscription($user);

        $telegram = $user->telegram()->first();
        $telegramUser = new TelegramUser($telegram->telegram_id, $telegram->username);

        try {
            $subscription->createSubscriberEvent($telegramUser);
        } catch (\Exception $e) {
            $this->assertEquals("Subscriber exist", $e->getMessage());
        }
    }

    /**
     * @return array[]
     */
    public function providerCreateSubscriberEventExist(): array
    {
        return [
            [1],
            [2]
        ];
    }

    /**
     * @dataProvider providerCreateSubscriberEventTelegramNotExist
     */
    public function testCreateSubscriberEventTelegramNotExist(
        int $idUser,
        int $telegramId,
        string $username
    ): void {
        $this->seed();

        $user = User::find($idUser);
        $subscription = new Subscription($user);

        $telegramUser = new TelegramUser($telegramId, $username);

        try {
            $subscription->createSubscriberEvent($telegramUser);
        } catch (\Exception $e) {
            $this->assertEquals("Telegram id not exist", $e->getMessage());
        }
    }

    /**
     * @return array[]
     */
    public function providerCreateSubscriberEventTelegramNotExist(): array
    {
        return [
            [3, -3, "oleg"],
            [4, 0, "egor"]
        ];
    }

    /**
     * @dataProvider providerDeleteSubscriberEvent
     */
    public function testDeleteSubscriberEvent(int $idUser): void
    {
        $this->seed();

        $this->assertTrue(SubscriberEvent::where("user_id", $idUser)->exists());

        $user = User::find($idUser);
        $subscription = new Subscription($user);

        $subscription->deleteSubscriberEvent();

        $this->assertFalse(SubscriberEvent::where("user_id", $idUser)->exists());
    }

    /**
     * @return array[]
     */
    public function providerDeleteSubscriberEvent(): array
    {
        return [
            [1],
            [2]
        ];
    }

    public function testGenerateConfirmCode(): void
    {
        $this->seed();

        $users = User::limit(5)->get();

        foreach ($users as $user) {
            $subscription = new Subscription($user);

            $this->assertIsString($subscription->generateConfirmCode());
        }
    }

    /**
     * @dataProvider providerIsSubscription
     */
    public function testIsSubscription(int $idUser, bool $expected): void
    {
        $this->seed();

        $user = User::find($idUser);
        $subscription = new Subscription($user);

        $this->assertEquals($expected, $subscription->isSubscription());
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
}
