<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Readable\ParentModel as ParentModel;
use PHPUnit\Framework\TestCase;

final class ParentModelTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        PersonShortModel $person,
        int $role
    ): void {
        $model = new ParentModel($person, $role);

        $this->assertInstanceOf(ParentModel::class, $model);
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($role, $model->getRole());
    }

    public function createProvider(): array
    {
        return [
            [new PersonShortModel(0, "Sidorov", collect([]), "Den", "Maksimovich", null), 45],
            [new PersonShortModel(1, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10"), 23],
        ];
    }
}
