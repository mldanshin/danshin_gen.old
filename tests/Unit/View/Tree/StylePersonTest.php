<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\StylePerson;
use App\View\Tree\Size;
use PHPUnit\Framework\TestCase;

final class StylePersonTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $margine,
        float $strokeWidth,
        int $padding,
        int $fontSize,
        int $lineSpacing,
        Size $button
    ): void {
        $obj = new StylePerson(
            $margine,
            $strokeWidth,
            $padding,
            $fontSize,
            $lineSpacing,
            $button
        );

        $this->assertInstanceOf(StylePerson::class, $obj);
        $this->assertEquals($margine, $obj->getMargine());
        $this->assertEquals($strokeWidth, $obj->getStrokeWidth());
        $this->assertEquals($padding, $obj->getPadding());
        $this->assertEquals($fontSize, $obj->getFontSize());
        $this->assertEquals($lineSpacing, $obj->getLineSpacing());
        $this->assertEquals($button, $obj->getButton());
    }

    /**
     * @return array[]
     */
    public function createProvider(): array
    {
        return [
            [2, 1, 4, 14, 2, new Size(12, 12)],
            [5, 0.2, 33, 16, 3, new Size(20, 26)],
        ];
    }
}
