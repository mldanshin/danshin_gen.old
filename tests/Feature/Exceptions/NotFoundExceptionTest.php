<?php

namespace Tests\Feature\Exceptions;

use App\Exceptions\NotFoundException;
use Tests\TestCase;

final class NotFoundExceptionTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate($value)
    {
        $this->assertInstanceOf(NotFoundException::class, new NotFoundException($value));
    }

    public function createProvider(): array
    {
        return [
            [null],
            [""],
            ["The person with id number = 9994442233 does not exist"],
        ];
    }

    public function testMessage()
    {
        $message = "The person with id number = 9994442233 does not exist";
        $exception = new NotFoundException($message);

        $this->assertTrue(str_contains($exception->__toString(), $message));
    }
}
