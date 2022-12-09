<?php

namespace App\Services\Events;

use App\Models\Eloquent\User;
use App\Repositories\Events\Events as Repository;
use App\Services\NotificationTypes;
use Illuminate\Support\Facades\Log;

final class Events
{
    private ?NotificationSender $sender = null;

    public function __construct(private Repository $repository)
    {
        $this->initializeSender();
    }

    public function send(): bool
    {
        if ($this->sender === null) {
            return true;
        } else {
            return $this->sender->send();
        }
    }

    private function initializeSender(): void
    {
        $model = $this->repository->get();
        if ($model->isEmpty()) {
            Log::info(self::class . "Events is missing");
            return;
        }

        $users = User::has("subscriptionEvent")->has("telegram")->get();
        if (empty($users) || $users->isEmpty()) {
            Log::info(self::class . "; User not has subscription event");
            return;
        }

        $this->sender = new NotificationSender(
            NotificationTypes::TELEGRAM,
            $model,
            $users
        );
    }
}
