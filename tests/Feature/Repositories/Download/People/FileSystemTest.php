<?php

namespace Tests\Feature\Repositories\Download\People;

use App\Repositories\Download\People\FileSystem as PeopleFileSystem;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class FileSystemTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $fileSystem = PeopleFileSystem::instance();
        $this->assertInstanceOf(PeopleFileSystem::class, $fileSystem);
    }

    public function testCreate(): void
    {
        $disk = Storage::fake("public");

        $fileSystem = new PeopleFileSystem($disk);
        $this->assertInstanceOf(PeopleFileSystem::class, $fileSystem);
    }

    /**
     * @dataProvider getPeoplePathProvider
     */
    public function testGetPeoplePath(string $extension): void
    {
        //preparation
        $disk = Storage::fake("public");
        $pathDirectory = $disk->path("download");
        $fileSystem = new PeopleFileSystem($disk);

        //execution
        $this->assertEquals($pathDirectory . "/genealogy.$extension", $fileSystem->getPeoplePath($extension));
    }

    /**
     * @return array[]
     */
    public function getPeoplePathProvider(): array
    {
        return [
            ["pdf"],
            ["odt"],
            ["txt"]
        ];
    }

    /**
     * @dataProvider getPersonPathProvider
     */
    public function testGetPersonPath(string $id, string $extension): void
    {
        //preparation
        $disk = Storage::fake("public");
        $pathDirectory = $disk->path("download");
        $fileSystem = new PeopleFileSystem($disk);

        //execution
        $this->assertEquals(
            $pathDirectory . "/person_$id.$extension",
            $fileSystem->getPersonPath($id, $extension)
        );
    }

    /**
     * @return array[]
     */
    public function getPersonPathProvider(): array
    {
        return [
            ["2", "pdf"],
            ["23", "odt"],
            ["55", "txt"]
        ];
    }
}
