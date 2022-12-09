<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Pair as PairModel;
use App\Models\Person\Editable\Form\ParentModel as ParentModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class ParentModelTest extends TestCase
{
    /**
     * @param Collection|PersonShortModel[] $personOptions
     * @param Collection|PairModel[] $roleOptions
     * @dataProvider createProvider
     */
    public function testCreate(
        int $person,
        Collection $personOptions,
        int $role,
        Collection $roleOptions
    ): void {
        $model = new ParentModel($person, $personOptions, $role, $roleOptions);

        $this->assertInstanceOf(ParentModel::class, $model);
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($personOptions, $model->getPersonOptions());
        $this->assertEquals($role, $model->getRole());
        $this->assertEquals($roleOptions, $model->getRoleOptions());
    }

    public function createProvider(): array
    {
        return [
            [
                10,
                collect([
                    new PersonShortModel(
                        10,
                        "Ivanov",
                        collect(["Sidorov", "Petrov"]),
                        "Ivan",
                        "Ivanovich",
                        "2000-01-10"
                    )
                ]),
                2,
                collect([new PairModel(1, "mother"), new PairModel(2, "father")]),
            ],
        ];
    }
}
