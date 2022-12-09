<?php

namespace Tests\Feature\Support;

use App\Support\DownloadRepository;
use App\Repositories\Download\FileSystem;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class DownloadRepositoryTest extends TestCase
{
    public function testCreate(): DownloadRepository
    {
        $obj = new DownloadRepository();
        $this->assertInstanceOf(DownloadRepository::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     */
    public function testClear(DownloadRepository $support): void
    {
        //prepare
        $directory = FileSystem::instance()->getPath();

        File::cleanDirectory($directory);

        $this->prepareFiles($directory);

        //testing
        $support->clear();

        //verify
        $this->assertCount(0, File::allFiles($directory));

        //clear
        File::cleanDirectory($directory);
    }

    /**
     * @return string[]
     */
    private function prepareFiles(string $directory): array
    {
        $files = [
            $directory . "fresh1.txt",
            $directory. "fresh2.txt",
            $directory. "fresh3.txt",
            $directory. "fresh4.txt"
        ];

        foreach ($files as $file) {
            File::put($file, "Bla Bla");
        }

        return $files;
    }
}
