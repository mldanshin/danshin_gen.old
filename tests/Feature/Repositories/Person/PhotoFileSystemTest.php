<?php

namespace Tests\Feature\Repositories\Person;

use App\Models\Download\Photo\FileArchive;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PhotoFileSystemTest extends TestCase
{
    use PhotoDataProvider;

    public function testCreateInstance(): void
    {
        $fileSystem = PhotoFileSystem::instance();
        $this->assertInstanceOf(PhotoFileSystem::class, $fileSystem);
    }

    public function testCreate(): PhotoFileSystem
    {
        $fileSystem = new PhotoFileSystem(Storage::fake("public"));
        $this->assertInstanceOf(PhotoFileSystem::class, $fileSystem);
        return $fileSystem;
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetPath
     */
    public function testGetPathRelative(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $this->assertEquals("photo/$expected", $fileSystem->getPathRelative($id, $fileName));
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetPath
     */
    public function testGetPath(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake("public")->path("photo/") . $expected;
        $this->assertEquals($expected, $fileSystem->getPath($id, $fileName));
    }

    /**
     * @return array[]
     */
    public function providerGetPath(): array
    {
        return [
            ["1", "1.txt", "1/1.txt"],
            ["4", "2.txt", "4/2.txt"],
            ["150", "345.txt", "150/345.txt"]
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetUrl
     */
    public function testGetUrl(
        string $id,
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake("public")->url("photo/") . $expected;
        $this->assertEquals($expected, $fileSystem->getUrl($id, $fileName));
    }

    /**
     * @return array[]
     */
    public function providerGetUrl(): array
    {
        return [
            ["1", "1.txt", "1/1.txt"],
            ["4", "2.txt", "4/2.txt"],
            ["150", "345.txt", "150/345.txt"]
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetUrls
     */
    public function testGetUrls(
        string $id,
        array $filesName,
        array $expected,
        PhotoFileSystem $fileSystem
    ): void {
        for ($i = 0; $i < count($filesName); $i++) {
            $expectedItem = Storage::fake("public")->url("photo/") . $expected[$i];
            $this->assertEquals($expectedItem, $fileSystem->getUrl($id, $filesName[$i]));
        }
    }

    /**
     * @return array[]
     */
    public function providerGetUrls(): array
    {
        return [
            [
                "1",
                ["1.txt", "2.txt", "3.txt"],
                ["1/1.txt", "1/2.txt", "1/3.txt"]
            ],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetPathTemp
     */
    public function testGetPathTempRelative(
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $this->assertEquals("photo_temp/$expected", $fileSystem->getPathTempRelative($fileName));
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetPathTemp
     */
    public function testGetPathTemp(
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake("public")->path("photo_temp/") . $expected;
        $this->assertEquals($expected, $fileSystem->getPathTemp($fileName));
    }

    /**
     * @return array[]
     */
    public function providerGetPathTemp(): array
    {
        return [
            ["1.txt", "1.txt"],
            ["2.txt", "2.txt"],
            ["345.txt", "345.txt"]
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetUrlTemp
     */
    public function testGetUrlTemp(
        string $fileName,
        string $expected,
        PhotoFileSystem $fileSystem
    ): void {
        $expected = Storage::fake("public")->url("photo_temp/") . $expected;
        $this->assertEquals($expected, $fileSystem->getUrlTemp($fileName));
    }

    /**
     * @return array[]
     */
    public function providerGetUrlTemp(): array
    {
        return [
            ["1.txt", "1.txt"],
            ["2.txt", "2.txt"],
            ["345.txt", "345.txt"]
        ];
    }

    public function testPutTemp(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        $file = new UploadedFile($this->getPathImage(), $this->getFileNameImage());

        //testing
        $fileSystem->putTemp($file);
        $this->assertFileExists($fileSystem->getPathTemp($file->hashName()));

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testMoveTempSuccess(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        for ($i = 0; $i < 5; $i++) {
            $pathPerson = $disk->path("photo/$i");
            File::makeDirectory($pathPerson);

            $fileTemp = "text.txt";
            $pathTemp = "photo_temp/$fileTemp";
            $disk->put($pathTemp, "Hello World!");

            //testing
            $fileSystem->moveTemp($i, collect([$fileTemp]));
            $this->assertFalse($disk->exists($pathTemp));
            $this->assertTrue($disk->exists("photo/$i/$fileTemp"));
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testMoveTempSuccessCreatePerson(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        for ($i = 0; $i < 5; $i++) {
            $fileTemp = "text.txt";
            $pathTemp = "photo_temp/$fileTemp";
            $disk->put($pathTemp, "Hello World!");

            //testing
            $fileSystem->moveTemp($i, collect([$fileTemp]));
            $this->assertFalse($disk->exists($pathTemp));
            $this->assertTrue($disk->exists("photo/$i/$fileTemp"));
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testMoveTempWrongNotFile(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        for ($i = 0; $i < 5; $i++) {
            $pathPerson = $disk->path("photo/$i");
            File::makeDirectory($pathPerson);

            $fileTemp = "text.txt";

            //testing
            try {
                $fileSystem->moveTemp($i, collect([$fileTemp]));
            } catch (\Exception $e) {
                $this->assertInstanceOf(\Exception::class, $e);
            }
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    /**
     * @depends testCreate
     */
    public function testGetPathDirectoryTemp(PhotoFileSystem $fileSystem): void
    {
        $expected = Storage::fake("public")->path("photo_temp") . "/";
        $this->assertEquals($expected, $fileSystem->getPathDirectoryTemp());
    }

    public function testExistsFileSuccess(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        //testing
        for ($i = 0; $i < 5; $i++) {
            $path = $disk->path("photo/text$i.txt");
            File::put($path, "Hello World!");
            $this->assertTrue($fileSystem->existsFile($path));
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testExistsFileWrong(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        //testing
        for ($i = 0; $i < 5; $i++) {
            $path = $disk->path("photo/text$i.txt");
            try {
                $fileSystem->existsFile($path);
            } catch (\Exception $e) {
                $this->assertInstanceOf(\Exception::class, $e);
            }
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testDeletePersonSuccess(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        for ($i = 0; $i < 5; $i++) {
            $pathDirectory = $disk->path("photo/$i");
            File::makeDirectory($pathDirectory);

            $pathFile = $disk->path("photo/$i/text.txt");
            File::put($pathFile, "Hello World!");

            //testing
            $this->assertTrue(File::exists($pathDirectory));
            $this->assertTrue(File::exists($pathFile));
            $this->assertTrue($fileSystem->deletePerson($i));
            $this->assertFalse(File::exists($pathDirectory));
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testDeletePersonWrong(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        for ($i = 0; $i < 5; $i++) {
            $pathDirectory = $disk->path("photo/$i");
            File::makeDirectory($pathDirectory);

            $pathFile = $disk->path("photo/$i/text.txt");
            File::put($pathFile, "Hello World!");

            File::chmod($pathDirectory, 0500);
        }

        //testing
        for ($i = 0; $i < 5; $i++) {
            try {
                $fileSystem->deletePerson($i);
            } catch (\Exception $e) {
                $this->assertInstanceOf(\Exception::class, $e);
            }
            $pathDirectory = $disk->path("photo/$i");
            File::chmod($pathDirectory, 0777);
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testDeletePersonFilesSuccess(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        $pathPerson = [];
        for ($i = 0; $i < 5; $i++) {
            $pathPerson[$i]["dir"] = $disk->path("photo/$i");
            File::makeDirectory($pathPerson[$i]["dir"]);

            //testing
            $this->assertTrue(File::exists($pathPerson[$i]["dir"]));

            for ($k = 0; $k < 5; $k++) {
                $pathPerson[$i]["file"][$k] = "text$k.txt";
                $pathPerson[$i]["path"][$k] = $disk->path("photo/$i/{$pathPerson[$i]["file"][$k]}");
                File::put($pathPerson[$i]["path"][$k], "Hello World!");

                //testing
                $this->assertTrue(File::exists($pathPerson[$i]["path"][$k]));
            }
        }

        //testing
        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($fileSystem->deletePersonFiles($i, collect($pathPerson[$i]["file"])));
            $this->assertTrue($fileSystem->deletePersonFiles($i, collect()));
            for ($k = 0; $k < 5; $k++) {
                $this->assertFalse(File::exists($pathPerson[$i]["path"][$k]));
                $this->assertTrue(File::exists($pathPerson[$i]["dir"]));
            }
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testDeletePersonFilesWrong(): void
    {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $fileSystem = new PhotoFileSystem($disk);

        $pathPerson = [];
        for ($i = 0; $i < 5; $i++) {
            $pathPerson[$i]["dir"] = $disk->path("photo/$i");
            File::makeDirectory($pathPerson[$i]["dir"]);

            //testing
            $this->assertTrue(File::exists($pathPerson[$i]["dir"]));

            for ($k = 0; $k < 5; $k++) {
                $pathPerson[$i]["file"][$k] = "text$k.txt";
                $pathPerson[$i]["path"][$k] = $disk->path("photo/$i/{$pathPerson[$i]["file"][$k]}");

                //testing
                $this->assertFalse(File::exists($pathPerson[$i]["path"][$k]));
            }
        }

        //testing
        for ($i = 0; $i < 5; $i++) {
            try {
                $this->assertTrue($fileSystem->deletePersonFiles($i, collect($pathPerson[$i]["file"])));
            } catch (\Exception $e) {
                $this->assertInstanceOf(\Exception::class, $e);
            }
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetBaseNames
     * @param Collection|string[] $path
     * @param Collection|string[] $expected
     */
    public function testGetBaseNames(Collection $path, Collection $expected, PhotoFileSystem $fileSystem): void
    {
        $this->assertEquals($expected, $fileSystem->getBaseNames($path));
    }

    /**
     * @return array[]
     */
    public function providerGetBaseNames(): array
    {
        return [
            [
                collect(["home/text.txt", "https://danshin.net/hello/file.html"]),
                collect(["text.txt", "file.html"])
            ]
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetBaseName
     */
    public function testGetBaseName(string $path, string $expected, PhotoFileSystem $fileSystem): void
    {
        $this->assertEquals($expected, $fileSystem->getBaseName($path));
    }

    /**
     * @return array[]
     */
    public function providerGetBaseName(): array
    {
        return [
            ["home/text.txt", "text.txt"],
            ["https://danshin.net/hello/file.html", "file.html"]
        ];
    }

    /**
     * @depends testCreate
     */
    public function testGetFilesArchive(): void {
        //preparation
        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $path = $disk->path("photo");

        $fileSystem = new PhotoFileSystem($disk);

        $personPhoto = $this->providerGetFilesArchive($path);

        foreach ($personPhoto as $key => $value) {
            File::makeDirectory("$path/$key");
            foreach ($value["file_names"] as $fileName) {
                File::copy($this->getPathImage(), "$path/$key/$fileName.jpg");
            }
        }

        //testing
        $filesArchive = $fileSystem->getFilesArchive();

        $this->assertCount(6, $filesArchive);

        $personPhoto = array_map(fn($item) => $item["expected"], $personPhoto);
        $array = [];
        foreach ($personPhoto as $item) {
            foreach ($item as $obj) {
                $array[] = $obj;
            }
        }

        for ($i = 0; $i < count($array); $i++) {
            $this->assertEquals($array[$i], $filesArchive[$i]);
        }
    }

    /**
     * @return array[]
     */
    private function providerGetFilesArchive(string $path): array
    {
        return [
            10 => [
                "file_names" => [1, 3],
                "expected" => [
                    new FileArchive("$path/10/1.jpg", "10/1.jpg"),
                    new FileArchive("$path/10/3.jpg", "10/3.jpg")
                ]
            ],
            14 => [
                "file_names" => [12, 5],
                "expected" => [
                    new FileArchive("$path/14/12.jpg", "14/12.jpg"),
                    new FileArchive("$path/14/5.jpg", "14/5.jpg")
                ]
            ],
            3 => [
                "file_names" => [6, 89],
                "expected" => [
                    new FileArchive("$path/3/6.jpg", "3/6.jpg"),
                    new FileArchive("$path/3/89.jpg", "3/89.jpg")
                ]
            ],
        ];
    }
}
