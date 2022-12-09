<?php

namespace App\Services\Events;

use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramUpdates;

final class ListenerTelegramBot
{
    public function __construct(private string $code)
    {
    }

    public function run(): ?TelegramUser
    {
        $startSeconds = time();
        $waitingTime = config("services.telegram-bot-api.waiting_time");
        $stopSeconds = $startSeconds + $waitingTime;

        while (true) {
            $updates = TelegramUpdates::create()
                ->options([
                    'timeout' => 0,
                ])
                ->get();

            if (time() >= $stopSeconds) {
                $this->createRecordLog($updates);
                return null;
            }

            if ($updates['ok']) {
                foreach ($updates["result"] as $item) {
                    if (isset($item["message"]["text"]) && $item["message"]["text"] == $this->code) {
                        return new TelegramUser(
                            $item["message"]["from"]["id"],
                            isset($item["message"]["from"]["username"]) ? $item["message"]["from"]["username"] : null
                        );
                    }
                }
            }
        }
    }

    /**
     * @param mixed[] $updates
     */
    private function createRecordLog(array $updates): void
    {
        if ($updates['ok']) {
            $json = json_encode($updates);
            Log::info("Expected code {$this->code}. Bot's response: " . $json);
        } else {
            Log::info("Response telegram bot error.");
        }
    }
}
