<?php

namespace Tests\Feature\Notifications\Events;

use App\Models\Eloquent\User;
use App\Models\Events\Birth;
use App\Models\Events\Events as Model;
use App\Models\Events\Person;
use App\Notifications\Events\Events;
use Illuminate\Foundation\Testing\RefreshDatabase;
use NotificationChannels\Telegram\TelegramMessage;
use Tests\TestCase;

final class EventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerCreate
     */
    public function testCreate(Model $model): void
    {
        $this->seed();

        $user = User::has("telegram")->get()->random();

        $obj = new Events($model);
        $telegramMessage = $obj->toTelegram($user);

        $this->assertInstanceOf(Events::class, $obj);
        $this->assertInstanceOf(TelegramMessage::class, $telegramMessage);
        $this->assertStringContainsString(
            "01.10.2000 [Ivanov Ivan Ivanovich]",
            $obj->toTelegram($user)->toArray()["text"]
        );
    }

    /**
     * @return array[]
     */
    public function providerCreate(): array
    {
        return [
            [
                new Model(
                    collect(),
                    collect([
                        new Birth(
                            "2000-10-01",
                            new Person(1, "Ivanov", "Ivan", "Ivanovich"),
                            new \DateInterval("P20Y")
                            )
                    ]),
                    collect()
                )
            ],
        ];
    }
}
