<?php

namespace Tests\Feature\Exceptions;

use App\Exceptions\SenderException;
use Tests\TestCase;

final class SenderExceptionTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate($value)
    {
        $this->assertInstanceOf(SenderException::class, new SenderException($value));
    }

    public function createProvider(): array
    {
        return [
            [null],
            [""],
            ["Failed to send a message to the number 9994442233"],
        ];
    }

    public function testMessage()
    {
        $message = "Failed to send a message to the number 9994442233";
        $exception = new SenderException($message);

        $this->assertTrue(str_contains($exception->__toString(), $message));
    }
}
