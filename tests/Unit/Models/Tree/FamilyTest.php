<?php

namespace Tests\Unit\Models\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

final class FamilyTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|PersonModel[] $marriage
     * @param Collection|FamilyModel[] $childrens
     */
    public function testCreate(
        PersonModel $person,
        Collection $marriage,
        Collection $childrens
    ): void {
        $model = new FamilyModel($person, $marriage, $childrens);

        $this->assertInstanceOf(FamilyModel::class, $model);
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($marriage, $model->getMarriage());
        $this->assertEquals($childrens, $model->getChildrens());
    }

    public function createProvider(): array
    {
        return [
            [
                new PersonModel(
                    1,
                    "Ivanov",
                    collect(),
                    "Ivan",
                    "Ivanovich",
                    "2000-01-10",
                    null,
                    true
                ),
                collect([
                    new PersonModel(
                        2,
                        "Ivanova",
                        collect(["Sidorova", "Petrova"]),
                        "Irina",
                        "Ivanovna",
                        "2001-01-10",
                        null,
                        false
                    ),
                ]),
                collect([
                    new FamilyModel(
                        new PersonModel(
                            3,
                            "Ivanov",
                            collect(),
                            "Egor",
                            "Ivanovich",
                            "2019-01-10",
                            null,
                            true
                        ),
                        collect(),
                        collect([
                            new PersonModel(
                                1,
                                "Ivanova",
                                collect(["Sidorova", "Petrova"]),
                                "Irina",
                                "Ivanovna",
                                "2021-01-10",
                                null,
                                false
                            ),
                        ]),
                    )
                ])
            ],
        ];
    }
}
