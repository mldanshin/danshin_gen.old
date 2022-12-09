<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\Image;
use App\View\Tree\PointXY;
use App\View\Tree\Size;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $width,
        int $height,
        string $href,
        int $x,
        int $y,
    ): void {
        $obj = new Image($width, $height, $href);
        $obj->setPoint($x, $y);

        $this->assertInstanceOf(Image::class, $obj);
        $this->assertEquals(new Size($width, $height), $obj->getSize());
        $this->assertEquals($href, $obj->getHref());
        $this->assertEquals(new PointXY($x, $y), $obj->getPoint());
    }

    public function createProvider(): array
    {
        return [
            [100, 200, "https://danshin.net/image.png", 100, 100],
            [45, 234, "https://test-go.ru/image.png", 200, 200],
        ];
    }
}
