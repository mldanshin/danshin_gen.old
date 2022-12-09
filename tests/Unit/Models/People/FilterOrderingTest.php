<?php

namespace Tests\Unit\Models\People;

use App\Models\Pair as PairModel;
use App\Models\People\FilterOrdering as FilterOrderingModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class FilterOrderingTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|PairModel[] $ordering
     */
    public function testCreate(
        string $search,
        Collection $ordering,
        int $orderingCurrent,
    ): void {
        $model = new FilterOrderingModel($search, $ordering, $orderingCurrent);

        $this->assertInstanceOf(FilterOrderingModel::class, $model);
        $this->assertEquals($search, $model->getSearch());
        $this->assertEquals($ordering, $model->getOrdering());
        $this->assertEquals($orderingCurrent, $model->getOrderingCurrent());
    }

    public function createProvider(): array
    {
        return [
            [
                "Petrov",
                collect([
                    new PairModel(1, "abc"),
                    new PairModel(2, "age")
                ]),
                1
            ],
        ];
    }
}
