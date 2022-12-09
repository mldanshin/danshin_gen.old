<?php

namespace Tests\Feature\Repositories\Download\Tree;

use App\Repositories\Download\Tree\FileSystem as TreeFileSystem;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class FileSystemTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $fileSystem = TreeFileSystem::instance();
        $this->assertInstanceOf(TreeFileSystem::class, $fileSystem);
    }

    public function testCreate(): void
    {
        $disk = Storage::fake("public");

        $fileSystem = new TreeFileSystem($disk);
        $this->assertInstanceOf(TreeFileSystem::class, $fileSystem);
    }

    /**
     * @dataProvider createFileSuccessProvider
     */
    public function testCreateFileSuccess(string $id, ?string $parentId, string $content, string $expected): void
    {
        //preparation
        $disk = Storage::fake("public");
        $fileSystem = new TreeFileSystem($disk);

        //execution
        $fileSystem->createFile($id, $parentId, $content);
        $this->assertFileExists($disk->path("download") . "/" . $expected);

        //clearing
        $this->cleanDirectory($disk);
    }

    /**
     * @return array[]
     */
    public function createFileSuccessProvider(): array
    {
        return [
            ["1", null, $this->getContent(), "tree_1.svg"],
            ["13", "145", $this->getContent(), "tree_13_145.svg"]
        ];
    }

    /**
     * @dataProvider createFileExceptionProvider
     */
    public function testCreateFileException(string $id, ?string $parentId, string $content, string $expected): void
    {
        //preparation
        $disk = Storage::fake("public");
        $fileSystem = new TreeFileSystem($disk);
        File::chmod($disk->path("download"), 0500);

        //execution
        try {
            $fileSystem->createFile($id, $parentId, $content);
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
        }

        //clearing
        File::chmod($disk->path("download"), 0777);
        $this->cleanDirectory($disk);
    }

    /**
     * @return array[]
     */
    public function createFileExceptionProvider(): array
    {
        return [
            ["1", null, $this->getContent(), "tree_1.svg"],
            ["13", "145", $this->getContent(), "tree_13_145.svg"]
        ];
    }

    /**
     * @dataProvider createFileErrorProvider
     */
    public function testCreateFileError(string $id, ?string $parentId, string $content, string $expected): void
    {
        //preparation
        $disk = Storage::fake("public");
        $fileSystem = new TreeFileSystem($disk);
        File::chmod($disk->path("download"), 0500);

        //execution
        try {
            $fileSystem->createFile($id, $parentId, $content);
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
        }

        //clearing
        File::chmod($disk->path("download"), 0777);
        $this->cleanDirectory($disk);
    }

    /**
     * @return array[]
     */
    public function createFileErrorProvider(): array
    {
        return [
            ["\0", "145", $this->getContent(), "tree_13_145.svg"]
        ];
    }

    private function cleanDirectory(Filesystem $disk): void
    {
        File::cleanDirectory($disk->path("download")) ;
    }

    private function getContent(): string
    {
        return <<< EOS
        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">
            <text x="0" y="20">Hello world!</text>
        </svg>
        EOS;
    }
}
