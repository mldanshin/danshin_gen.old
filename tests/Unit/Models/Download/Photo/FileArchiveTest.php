<?php

namespace Tests\Unit\Models\Download\Photo;

use App\Models\Download\Photo\FileArchive;
use PHPUnit\Framework\TestCase;

final class FileArchiveTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        string $path,
        string $entryName
    ): void {
        $model = new FileArchive($path, $entryName);

        $this->assertInstanceOf(FileArchive::class, $model);
        $this->assertEquals($path, $model->getPath());
        $this->assertEquals($entryName, $model->getEntryName());
    }

    public function createProvider(): array
    {
        return [
            ["/home/10/1.jpg", "10/1.jpg"],
            ["/home/12/2.jpg", "12/2.jpg"],
        ];
    }
}
