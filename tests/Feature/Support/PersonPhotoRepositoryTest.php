<?php

namespace Tests\Feature\Support;

use App\Support\PersonPhotoRepository;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PersonPhotoRepositoryTest extends TestCase
{
    use PhotoDataProvider;

    public function testCreate(): void
    {
        $obj = new PersonPhotoRepository();
        $this->assertInstanceOf(PersonPhotoRepository::class, $obj);
    }

    public function testClearTmpDir(): void
    {
        $countFresh = 4;
        $countOld = 5;

        $this->setConfigFakeDisk();

        //prepare
        $disk = Storage::fake("public");
        $fileSystem = new PhotoFileSystem($disk);

        $this->prepareFreshFiles($countFresh, $fileSystem);
        $this->prepareOldFiles($countOld, $fileSystem);

        //testing
        $obj = new PersonPhotoRepository();
        $obj->clearTempDir();

        //verify
        $files = File::files($fileSystem->getPathDirectoryTemp());
        $this->assertTrue(count($files) === $countFresh);
    }

    private function prepareFreshFiles(int $count, PhotoFileSystem $fileSystem): void
    {
        for ($i = 0; $i < $count; $i++) {
            $fileTmp = $fileSystem->getPathTemp("fresh{$i}.png");
            File::copy($this->getPathImage(), $fileTmp);
        }
    }

    private function prepareOldFiles(int $count, PhotoFileSystem $fileSystem): void
    {
        $timeCurrent = time();
        $timefileStorage = config("app.storage.photo.time_files_temp");

        for ($i = 0; $i < $count; $i++) {
            $fileTmp = $fileSystem->getPathTemp("old{$i}.png");
            File::copy($this->getPathImage(), $fileTmp);
            touch(
                $fileTmp,
                $timeCurrent - ($timefileStorage + 10)
            );
        }
    }
}
