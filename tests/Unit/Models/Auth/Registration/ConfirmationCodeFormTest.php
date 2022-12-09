<?php

namespace Tests\Unit\Models\Auth\Registration;

use App\Models\Auth\Registration\ConfirmationCodeForm;
use PHPUnit\Framework\TestCase;

final class ConfirmationCodeFormTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $id,
        string $attempts,
        string $timestamp,
        string $repeatTimestamp
    ): void {
        $model = new ConfirmationCodeForm($id, $attempts, $timestamp, $repeatTimestamp);

        $this->assertInstanceOf(ConfirmationCodeForm::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($attempts, $model->getAttempts());
        $this->assertEquals($timestamp, $model->getTimestamp());
        $this->assertEquals($repeatTimestamp, $model->getRepeatTimestamp());
    }

    public function createProvider(): array
    {
        return [
            ["1", "2", "12344566", "4567788"],
            ["4", "1", "98765", "54321098"],
        ];
    }
}
