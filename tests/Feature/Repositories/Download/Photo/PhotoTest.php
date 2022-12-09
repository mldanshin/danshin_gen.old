<?php

namespace Tests\Feature\Repositories\Download\Photo;

use App\Repositories\Download\FileSystem;
use App\Repositories\Download\Photo\Download;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PhotoTest extends TestCase
{
    use PhotoDataProvider;
    use RefreshDatabase;

    public function testCreate(): void
    {
        //preparation
        $this->seed();

        $disk = Storage::fake("public");
        $fileSystem = new FileSystem($disk);
        $photoFileSystem = new PhotoFileSystem($disk);

        $this->seedPhoto($disk);

        $expectedPath = $fileSystem->getPath();

        $fileSystem->getDisk()->delete($expectedPath);

        //testing
        $obj = new Download($fileSystem, $photoFileSystem);
        $actualPath = $obj->getPath();

        $this->assertEquals("{$expectedPath}photo-genealogy.zip", $actualPath);
        $this->assertFileExists($actualPath);
    }

    public function testCreateEmpty(): void
    {
        //preparation
        $this->seed();

        $disk = Storage::fake("public");
        $fileSystem = new FileSystem($disk);
        $photoFileSystem = new PhotoFileSystem($disk);

        $expectedPath = $fileSystem->getPath();

        $fileSystem->getDisk()->delete($expectedPath);

        //testing
        $obj = new Download($fileSystem, $photoFileSystem);
        $actualPath = $obj->getPath();

        $this->assertNull($actualPath);
    }
}
