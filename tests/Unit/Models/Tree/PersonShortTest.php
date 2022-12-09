<?php

namespace Tests\Unit\Models\Tree;

use App\Models\Tree\PersonShort as PersonShortModel;
use PHPUnit\Framework\TestCase;

final class PersonShortTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        ?string $surname,
        ?string $name,
        ?string $patronymic
    ): void {
        $model = new PersonShortModel($id, $surname, $name, $patronymic);

        $this->assertInstanceOf(PersonShortModel::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
    }

    public function createProvider(): array
    {
        return [
            [1, "Ivanov", "Ivan", "Ivanovich"],
            [1, "Petrov", null, "Petrovich"],
            [2, "Sidorov", "Den", "Maksimovich"],
            [3, "Sidorov", "Den", null],
        ];
    }
}
