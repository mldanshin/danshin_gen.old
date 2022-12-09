<?php

namespace Tests\Feature\Repositories\People\Ordering;

use App\Repositories\People\Ordering\Map;
use App\Repositories\People\Ordering\OrderingContract;
use Illuminate\Support\Collection;
use Tests\TestCase;

class MapTest extends TestCase
{
    public function testCreate(): Map
    {
        $obj = new Map();
        $this->assertInstanceOf(Map::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     * @dataProvider getSorterProvider
     */
    public function testGetSorter($value, Map $map)
    {
        $this->assertInstanceOf(OrderingContract::class, $map->getSorter($value));
    }

    public function getSorterProvider(): array
    {
        return [
            [1],
            [2],
            [null]
        ];
    }

    /**
     * @depends testCreate
     */
    public function testGet(Map $map)
    {
        $this->assertInstanceOf(Collection::class, $map->get());
    }
}
