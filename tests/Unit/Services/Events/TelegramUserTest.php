<?php

namespace Tests\Unit\Services\Events;

use App\Services\Events\TelegramUser;
use PHPUnit\Framework\TestCase;

final class TelegramUserTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $id,
        ?string $username
    ): void {
        $object = new TelegramUser($id, $username);

        $this->assertInstanceOf(TelegramUser::class, $object);
        $this->assertEquals($id, $object->getId());
        $this->assertEquals($username, $object->getUsername());
    }

    public function createProvider(): array
    {
        return [
            ["123454", "@mldanshin"],
            ["4987654", "@dendanshin"],
            ["5678943", null],
        ];
    }
}
