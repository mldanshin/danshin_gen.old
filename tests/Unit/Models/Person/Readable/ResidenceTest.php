<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\Person\Readable\Residence as ResidenceModel;
use PHPUnit\Framework\TestCase;

final class ResidenceTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $name,
        ?string $date
    ): void {
        $model = new ResidenceModel($name, $date);

        $this->assertInstanceOf(ResidenceModel::class, $model);
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($date, $model->getDate());
    }

    public function createProvider(): array
    {
        return [
            ["Kemerovo", "2020-01-01"],
            ["Novosibirsk", null],
        ];
    }
}
