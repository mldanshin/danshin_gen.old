<?php

namespace Tests\Feature\Repositories\Download;

use App\Repositories\Download\FileSystem as DownloadFileSystem;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class FileSystemTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $fileSystem = DownloadFileSystem::instance();
        $this->assertInstanceOf(DownloadFileSystem::class, $fileSystem);
    }

    public function testCreate(): DownloadFileSystem
    {
        $disk = Storage::fake("public");

        $fileSystem = new DownloadFileSystem($disk);
        $this->assertInstanceOf(DownloadFileSystem::class, $fileSystem);

        return $fileSystem;
    }

    /**
     * @depends testCreate
     */
    public function testPath(DownloadFileSystem $fileSystem): void
    {
        $disk = Storage::fake("public");
        $expected = $disk->path("download") . "/";
        $this->assertEquals($expected, $fileSystem->getPath());
    }

    public function testDisk(): void
    {
        $disk = Storage::fake("public");
        $fileSystem = new DownloadFileSystem($disk);
        $this->assertEquals($disk, $fileSystem->getDisk());
    }
}
