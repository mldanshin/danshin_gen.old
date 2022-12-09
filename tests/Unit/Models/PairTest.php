<?php

namespace Tests\Unit\Models;

use App\Models\Pair as PairModel;
use PHPUnit\Framework\TestCase;

final class PairTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        string $name
    ): void {
        $model = new PairModel($id, $name);

        $this->assertInstanceOf(PairModel::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($name, $model->getName());
    }

    public function createProvider(): array
    {
        return [
            [1, "name"],
            [4, "type"],
        ];
    }
}
