<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\Person\Readable\Photo as PhotoModel;
use PHPUnit\Framework\TestCase;

final class PhotoTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $url,
        string $path,
        ?string $date
    ): void {
        $model = new PhotoModel($url, $path, $date);

        $this->assertInstanceOf(PhotoModel::class, $model);
        $this->assertEquals($url, $model->getUrl());
        $this->assertEquals($path, $model->getPath());
        $this->assertEquals($date, $model->getDate());
    }

    public function createProvider(): array
    {
        return [
            ["https://youtube.com", "home/storage/public/app/photo/1.jpg", "2000-01-09"],
            ["https://danshin.net", "home/storage/public/app/photo/2.jpg", null],
        ];
    }
}
