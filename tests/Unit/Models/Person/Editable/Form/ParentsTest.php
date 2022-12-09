<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Pair as PairModel;
use App\Models\Person\Editable\Form\Parents as ParentsModel;
use App\Models\Person\Editable\Form\ParentModel as ParentModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class ParentsTest extends TestCase
{
    /**
     * @param Collection|PairModel[] $roleOptions
     * @param Collection|ParentModel[] $parent
     * @dataProvider createProvider
     */
    public function testCreate(
        Collection $roleOptions,
        Collection $parent
    ): void {
        $model = new ParentsModel($roleOptions, $parent);

        $this->assertInstanceOf(ParentsModel::class, $model);
        $this->assertEquals($roleOptions, $model->getRoleOptions());
        $this->assertEquals($parent, $model->getParent());
    }

    public function createProvider(): array
    {
        return [
            [
                collect([new PairModel(1, "mother"), new PairModel(2, "father")]),
                collect([
                    new ParentModel(
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
                    )
                ])
            ]
        ];
    }
}
