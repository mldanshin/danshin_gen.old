<?php

namespace Tests\Feature\Repositories\People;

use App\Models\People\FilterOrdering as FilterOrderingModel;
use App\Repositories\People\FilterOrdering as FilterOrderingRepositories;
use App\Repositories\People\Ordering\Map as OrderingMap;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class FilterOrderingTest extends TestCase
{
    use WithFaker;

    public function testCreate(): FilterOrderingRepositories
    {
        $obj = new FilterOrderingRepositories(new OrderingMap());
        $this->assertInstanceOf(FilterOrderingRepositories::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     */
    public function testGet(FilterOrderingRepositories $repository)
    {
        $orderingMap = new OrderingMap();

        $peopleOrdering = array_merge($orderingMap->getKeys(), [null]);

        for ($i = 0; $i < 10; $i++) {
            $model = $repository->get(
                $this->faker->randomElement([$this->faker->word(), null]),
                $this->faker->randomElement($peopleOrdering),
            );
            $this->assertInstanceOf(FilterOrderingModel::class, $model);
        }
    }
}
