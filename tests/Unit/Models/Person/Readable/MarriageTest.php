<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Readable\Marriage as MarriageModel;
use PHPUnit\Framework\TestCase;

final class MarriageTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        PersonShortModel $soulmate,
        int $role
    ): void {
        $model = new MarriageModel($soulmate, $role);

        $this->assertInstanceOf(MarriageModel::class, $model);
        $this->assertEquals($soulmate, $model->getSoulmate());
        $this->assertEquals($role, $model->getRole());
    }

    public function createProvider(): array
    {
        return [
            [new PersonShortModel(0, "Sidorov", collect([]), "Den", "Maksimovich", null), 2],
            [new PersonShortModel(1, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10"), 50],
        ];
    }
}
