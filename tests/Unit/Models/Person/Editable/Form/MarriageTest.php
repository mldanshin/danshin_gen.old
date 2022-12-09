<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\Pair as PairModel;
use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Editable\Form\Marriage as MarriageModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class MarriageTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|Pair[] $roleCurrentOptions
     * @param Collection|PersonShort[] $soulmateOptions
     * @param Collection|Pair[] $roleSoulmateOptions
     */
    public function testCreate(
        int $roleCurrent,
        Collection $roleCurrentOptions,
        int $soulmate,
        Collection $soulmateOptions,
        int $roleSoulmate,
        Collection $roleSoulmateOptions
    ): void {
        $model = new MarriageModel(
            $roleCurrent,
            $roleCurrentOptions,
            $soulmate,
            $soulmateOptions,
            $roleSoulmate,
            $roleSoulmateOptions
        );

        $this->assertInstanceOf(MarriageModel::class, $model);
        $this->assertEquals($roleCurrent, $model->getRoleCurrent());
        $this->assertEquals($roleCurrentOptions, $model->getRoleCurrentOptions());
        $this->assertEquals($soulmate, $model->getSoulmate());
        $this->assertEquals($soulmateOptions, $model->getSoulmateOptions());
        $this->assertEquals($roleSoulmate, $model->getRoleSoulmate());
        $this->assertEquals($roleSoulmateOptions, $model->getRoleSoulmateOptions());
    }

    public function createProvider(): array
    {
        return [
            [
                12,
                collect([new PairModel(1, "wife"), new PairModel(2, "husband")]),
                10,
                collect([
                    new PersonShortModel(10, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10")
                ]),
                2,
                collect([new PairModel(1, "wife"), new PairModel(2, "husband")]),
            ],
        ];
    }
}
