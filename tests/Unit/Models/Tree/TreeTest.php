<?php

namespace Tests\Unit\Models\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Tree as TreeModel;
use PHPUnit\Framework\TestCase;

final class TreeTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        PersonShortModel $personTarget,
        FamilyModel $family,
    ): void {
        $model = new TreeModel($personTarget, $family);

        $this->assertInstanceOf(TreeModel::class, $model);
        $this->assertEquals($personTarget, $model->getPersonTarget());
        $this->assertEquals($family, $model->getFamily());
    }

    public function createProvider(): array
    {
        return [
            [
                new PersonShortModel(1, "Ivanov", "Ivan", "Ivanovich"),
                new FamilyModel(
                    new PersonModel(
                        1,
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
                            2,
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
            ]
        ];
    }
}
