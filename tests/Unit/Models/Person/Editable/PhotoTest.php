<?php

namespace Tests\Unit\Models\Person\Editable;

use App\Models\Person\Editable\Photo as PhotoModel;
use PHPUnit\Framework\TestCase;

final class PhotoTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $url,
        string $pathRelative,
        ?string $date,
        int $order
    ): void {
        $model = new PhotoModel($url, $pathRelative, $date, $order);

        $this->assertInstanceOf(PhotoModel::class, $model);
        $this->assertEquals($url, $model->getUrl());
        $this->assertEquals($pathRelative, $model->getPathRelative());
        $this->assertEquals($date, $model->getDate());
        $this->assertEquals($order, $model->getOrder());
    }

    public function createProvider(): array
    {
        return [
            ["https://test-go.ru/image.png", "img/image.png", null, 1],
            ["https://danshin.net/image.png", "photo/image.png", "2000-01-01", 3],
        ];
    }
}
