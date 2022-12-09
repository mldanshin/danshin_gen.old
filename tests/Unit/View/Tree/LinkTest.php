<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\Link;
use App\View\Tree\PointXY;
use App\View\Tree\Size;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $personId,
        string $href,
        string $hrefPart,
        string $imageHref,
        Size $size,
        ?PointXY $point,
        int $x,
        int $y
    ): void {
        if ($point !== null) {
            $obj = new Link($personId, $href, $hrefPart, $imageHref, $size, $point);
        } else {
            $obj = new Link($personId, $href, $hrefPart, $imageHref, $size);
            $obj->setPoint($x, $y);
            $point = new PointXY($x, $y);
        }

        $this->assertInstanceOf(Link::class, $obj);
        $this->assertEquals($personId, $obj->getPersonId());
        $this->assertEquals($href, $obj->getHref());
        $this->assertEquals($imageHref, $obj->getImageHref());
        $this->assertEquals($size, $obj->getSize());
        $this->assertEquals($point, $obj->getPoint());
    }

    public function createProvider(): array
    {
        return [
            [
                "1",
                "https://danshin.net/person",
                "https://danshin.net/partials/person",
                "https://danshin.net/image.png",
                new Size(10, 20),
                new PointXY(23, 29),
                29,
                40
            ],
            [
                "4",
                "https://test-go.ru/person",
                "https://test-go.ru/partials/person",
                "https://test-go.ru/image.png",
                new Size(30, 60),
                null,
                90,
                45
            ],
        ];
    }
}
