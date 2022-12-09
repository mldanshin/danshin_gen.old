<?php

namespace Tests\Unit\Models\Person\Editable;

use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use PHPUnit\Framework\TestCase;

final class OldSurnameTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $surname,
        int $order
    ): void {
        $model = new OldSurnameModel($surname, $order);

        $this->assertInstanceOf(OldSurnameModel::class, $model);
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($order, $model->getOrder());
    }

    public function createProvider(): array
    {
        return [
            ["Ivanod", 10],
            ["Petrov", 18],
        ];
    }
}
