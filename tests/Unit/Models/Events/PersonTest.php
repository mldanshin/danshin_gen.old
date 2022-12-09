<?php

namespace Tests\Unit\Models\Events;

use App\Models\Events\Person as PersonModel;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        string $surname,
        string $name,
        ?string $patronymic
    ): void {
        $model = new PersonModel($id, $surname, $name, $patronymic);

        $this->assertInstanceOf(PersonModel::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
    }

    public function createProvider(): array
    {
        return [
            [1, "Ivanov", "Ivan", "Ivanovich"],
            [2, "Ivanov", "Ivan", "Ivanovich"],
        ];
    }
}
