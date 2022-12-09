<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\PointXY;
use PHPUnit\Framework\TestCase;

final class PointXYTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $x,
        int $y
    ): void {
        $obj = new PointXY($x, $y);

        $this->assertInstanceOf(PointXY::class, $obj);
        $this->assertEquals($x, $obj->getX());
        $this->assertEquals($y, $obj->getY());
    }

    public function createProvider(): array
    {
        return [
            [100, 200],
            [45, 234],
        ];
    }
}
