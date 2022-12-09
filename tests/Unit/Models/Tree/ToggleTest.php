<?php

namespace Tests\Unit\Models\Tree;

use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Toggle as ToggleModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class ToggleTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|PersonShortModel[] $list
     */
    public function testCreate(
        Collection $list,
        int $active
    ): void {
        $model = new ToggleModel($list, $active);

        $this->assertInstanceOf(ToggleModel::class, $model);
        $this->assertEquals($list, $model->getList());
        $this->assertEquals($active, $model->getActive());
    }

    public function createProvider(): array
    {
        return [
            [
                collect([
                    new PersonShortModel(1, "Ivanov", "Ivan", "Ivanovich"),
                    new PersonShortModel(3, "Petrov", null, "Petrovich"),
                    new PersonShortModel(2, "Sidorov", "Den", "Maksimovich"),
                ]),
                1
            ],
            [
                collect([
                    new PersonShortModel(3, "Sidorov", "Den", null),
                ]),
                3
            ]
        ];
    }
}
