<?php

namespace Tests\Unit\View\Tree;

use App\View\Tree\Size;
use PHPUnit\Framework\TestCase;

final class SizeTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $width,
        int $height
    ): void {
        $size = new Size($width, $height);

        $this->assertInstanceOf(Size::class, $size);
        $this->assertEquals($width, $size->getWidth());
        $this->assertEquals($height, $size->getHeight());
    }

    public function createProvider(): array
    {
        return [
            [100, 200],
            [45, 234],
        ];
    }
}
