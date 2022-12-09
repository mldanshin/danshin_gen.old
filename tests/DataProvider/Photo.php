<?php

namespace Tests\DataProvider;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Storage as StorageDataProvider;

trait Photo
{
    use StorageDataProvider;

    private function getPathImage(): string
    {
        return base_path("storage/framework/testing/test.png");
    }

    private function getFileNameImage(): string
    {
        return "test.png";
    }

    private function createDirectory(Filesystem $disk): void
    {
        if (!$disk->exists("photo")) {
            File::makeDirectory($disk->path("photo"));
        }

        if (!$disk->exists("photo")) {
            File::makeDirectory($disk->path("photo_temp"), force: true);
        }
    }

    private function cleanDirectory(Filesystem $disk = null): void
    {
        if ($disk === null) {
            $disk = Storage::fake("public");
        }

        File::cleanDirectory($disk->path("photo"));
        File::cleanDirectory($disk->path("photo_temp/"));
    }
    
    /**
     * defaul fake disk
     */
    private function seedPhoto(Filesystem $disk = null): void
    {
        if ($disk === null) {
            $disk = Storage::fake("public");
        }

        $this->createDirectory($disk);
        $people = PeopleEloquentModel::has("photo")->get();
        $this->createFile($people, $disk->path("photo"));
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     */
    private function createFile(Collection $people, string $path): void
    {
        $pathTestImage = $this->getPathImage();

        foreach ($people as $person) {
            File::makeDirectory("$path/$person->id");
            $photo = $person->photo()->get();
            foreach ($photo as $item) {
                File::copy($pathTestImage, $path . "/" . $person->id . "/". $item->file);
            }
        }
    }

    private function createTempPhotoFile(Filesystem $disk = null): string
    {
        $fileSystem = ($disk === null) ? PhotoFileSystem::instance() : new PhotoFileSystem($disk);

        $file = new UploadedFile($this->getPathImage(), $this->getFileNameImage());
        $fileName = $fileSystem->putTemp($file);

        return $fileSystem->getPathTempRelative($fileName);
    }
}
