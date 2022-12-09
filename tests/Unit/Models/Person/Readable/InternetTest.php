<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\Person\Readable\Internet as InternetModel;
use PHPUnit\Framework\TestCase;

final class InternetTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $url,
        string $name
    ): void {
        $model = new InternetModel($url, $name);

        $this->assertInstanceOf(InternetModel::class, $model);
        $this->assertEquals($url, $model->getUrl());
        $this->assertEquals($name, $model->getName());
    }

    public function createProvider(): array
    {
        return [
            ["https://youtube.com", "youtube"],
            ["https://danshin.net", "MySite"],
        ];
    }
}
