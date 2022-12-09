<?php

namespace Tests\Feature\Services\Events;

use App\Services\Events\ListenerTelegramBot;
use Tests\TestCase;

final class ListenerTelegramBotTest extends TestCase
{
    /**
     * @dataProvider providerCreate
     */
    public function testCreate(string $code): void
    {
        $object = new ListenerTelegramBot($code);

        $this->assertInstanceOf(ListenerTelegramBot::class, $object);
    }

    /**
     * @return array[]
     */
    public function providerCreate(): array
    {
        return [
            ["4321"],
            ["5678"],
            ["2345"]
        ];
    }

    /**
     * @dataProvider providerTimeIsUp
     */
    public function testRunTimeIsUp(string $code): void
    {
        config(["services.telegram-bot-api.waiting_time" => 2]);

        $object = new ListenerTelegramBot($code);

        $this->assertNull($object->run());
    }

    /**
     * @return array[]
     */
    public function providerTimeIsUp(): array
    {
        return [
            ["3456"],
            ["1234"]
        ];
    }
}
