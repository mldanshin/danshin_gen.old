<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\PointXY;
use App\View\Tree\Text;
use PHPUnit\Framework\TestCase;

final class TextTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $content,
        ?PointXY $point,
        int $x,
        int $y
    ): void {
        if ($point === null) {
            $obj = new Text($content);
            $obj->setPoint($x, $y);
            $point = new PointXY($x, $y);
        } else {
            $obj = new Text($content, $point);
        }

        $this->assertInstanceOf(Text::class, $obj);
        $this->assertEquals($content, $obj->getContent());
        $this->assertEquals($point, $obj->getPoint());
    }

    public function createProvider(): array
    {
        return [
            ["Hello world", new PointXY(22, 44), 100, 200],
            ["Blabla", null, 45, 234],
        ];
    }
}
