<?php

namespace App\Repositories\Events;

use App\Models\Eloquent\SubscriberEvent;
use App\Models\Eloquent\Telegram;
use App\Models\Eloquent\User;
use App\Services\Events\TelegramUser;

final class Subscription
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function create(TelegramUser $telegramUser): bool
    {
        if (!$this->createOrUpdadeTelegram($telegramUser)) {
            return false;
        }

        $this->createSubscriberEvent($telegramUser);

        return true;
    }

    public function createOrUpdadeTelegram(TelegramUser $telegramUser): bool
    {
        Telegram::updateOrCreate(
            [ "person_id" => $this->user->person_id, "telegram_id" => $telegramUser->getId()],
            ["telegram_username" => $telegramUser->getUsername()]
        );
        return true;
    }

    /**
     * @throws \Exception
     */
    public function createSubscriberEvent(TelegramUser $telegramUser): void
    {
        $telegram = Telegram::where("telegram_id", $telegramUser->getId())->first();

        if ($telegram === null) {
            throw new \Exception("Telegram id not exist");
        }

        if (SubscriberEvent::where("user_id", $this->user->id)->exists()) {
            throw new \Exception("Subscriber exist");
        }

        SubscriberEvent::create([
            "user_id" => $this->user->id,
            "telegram_id" => $telegram->id
        ]);
    }

    public function deleteSubscriberEvent(): void
    {
        SubscriberEvent::where("user_id", $this->user->id)->delete();
    }

    public function generateConfirmCode(): string
    {
        if (empty(session("subscription_create_code"))) {
            $value = uniqid();
            session(["subscription_create_code" => $value]);
            return $value;
        } else {
            return session("subscription_create_code");
        }
    }

    public function isSubscription(): bool
    {
        return $this->user->isSubscription();
    }
}
