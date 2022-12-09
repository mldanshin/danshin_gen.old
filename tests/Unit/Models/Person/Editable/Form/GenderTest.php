<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\Pair as PairModel;
use App\Models\Person\Editable\Form\Gender as GenderModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class GenderTest extends TestCase
{
    /**
     * @param Collection|PairModel[] $options
     * @dataProvider createProvider
     */
    public function testCreate(
        Collection $options,
        int $type,
    ): void {
        $model = new GenderModel($options, $type);

        $this->assertInstanceOf(GenderModel::class, $model);
        $this->assertEquals($options->all(), $model->getOptions()->all());
        $this->assertEquals($type, $model->getType());
    }

    public function createProvider(): array
    {
        return [
            [
                collect([new PairModel(1, "man"), new PairModel(2, "woman")]),
                2
            ],
            [
                collect([new PairModel(10, "www"), new PairModel(23, "eeee")]),
                10
            ]
        ];
    }
}
