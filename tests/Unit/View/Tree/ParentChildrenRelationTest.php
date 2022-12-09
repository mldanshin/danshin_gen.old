<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\ParentChildrenRelation;
use App\View\Tree\PointXY;
use PHPUnit\Framework\TestCase;

final class ParentChildrenRelationTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        PointXY $point1,
        PointXY $point2
    ): void {
        $obj = new ParentChildrenRelation($point1, $point2);

        $this->assertInstanceOf(ParentChildrenRelation::class, $obj);
        $this->assertEquals($point1, $obj->getPoint1());
        $this->assertEquals($point2, $obj->getPoint2());
    }

    public function createProvider(): array
    {
        return [
            [new PointXY(10, 10), new PointXY(20, 20)],
            [new PointXY(40, 40), new PointXY(110, 10)],
        ];
    }
}
