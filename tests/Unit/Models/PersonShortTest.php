<?php

namespace Tests\Unit\Models;

use App\Models\PersonShort as PersonShortModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

final class PersonShortTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        ?string $surname,
        ?Collection $oldSurname,
        ?string $name,
        ?string $patronymic,
        ?string $birthDate
    ): void {
        $model = new PersonShortModel($id, $surname, $oldSurname, $name, $patronymic, $birthDate);

        $this->assertEquals($id, $model->getId());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($oldSurname->all(), $model->getOldSurname()->all());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
        $this->assertEquals($birthDate, $model->getBirthDate());
    }

    public function createProvider(): array
    {
        return [
            [1, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10"],
            [1, "Petrov", collect([]), null, "Petrovich", "2000-01-10"],
            [2, "Sidorov", collect([]), "Den", "Maksimovich", null],
            [3, "Sidorov", collect([]), "Den", null, "2013-01-??"],
        ];
    }
}
