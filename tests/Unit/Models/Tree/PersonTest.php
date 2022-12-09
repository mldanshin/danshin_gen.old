<?php

namespace Tests\Unit\Models\Tree;

use App\Models\Tree\Person as PersonModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

final class PersonTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        string $surname,
        ?Collection $oldSurname,
        string $name,
        ?string $patronymic,
        string $birthDate,
        ?string $deathDate,
        bool $isPersonTarget
    ): void {
        $model = new PersonModel(
            $id, $surname,
            $oldSurname,
            $name,
            $patronymic,
            $birthDate,
            $deathDate,
            $isPersonTarget
        );

        $this->assertEquals($id, $model->getId());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($oldSurname->all(), $model->getOldSurname()->all());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
        $this->assertEquals($birthDate, $model->getBirthDate());
        $this->assertEquals($deathDate, $model->getDeathDate());
        $this->assertEquals($isPersonTarget, $model->isPersonTarget());
    }

    public function createProvider(): array
    {
        return [
            [1, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10", null, false],
            [2, "Petrov", collect([]), "", "Petrovich", "2000-01-10", "2020-10-01", true],
            [3, "Sidorov", collect([]), "Den", "Maksimovich", "", null, false],
            [4, "Ivanov", collect([]), "", "Maksimovich", "????-10-01", null, true],
            [5, "Ivanov", collect(["Petrov"]), "Den", "Ivanovich", "????-10-01", "2020-??-10", false],
        ];
    }
}
