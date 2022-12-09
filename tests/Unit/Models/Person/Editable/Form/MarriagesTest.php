<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\Pair as PairModel;
use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Editable\Form\Marriages as MarriagesModel;
use App\Models\Person\Editable\Form\Marriage as MarriageModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class MarriagesTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|Pair[] $roleOptions
     * @param Collection|Marriage[] $marriage
     */
    public function testCreate(
        Collection $roleOptions,
        Collection $marriage
    ): void {
        $model = new MarriagesModel($roleOptions, $marriage);

        $this->assertInstanceOf(MarriagesModel::class, $model);
        $this->assertEquals($roleOptions, $model->getRoleOptions());
        $this->assertEquals($marriage, $model->getMarriage());
    }

    public function createProvider(): array
    {
        return [
            [
                collect([new PairModel(1, "wife"), new PairModel(2, "husband")]),
                collect([
                    new MarriageModel(
                        2,
                        collect([new PairModel(1, "wife"), new PairModel(2, "husband")]),
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
                        collect([new PairModel(1, "wife"), new PairModel(2, "husband")]),
                    )
                ])
            ],
        ];
    }
}
