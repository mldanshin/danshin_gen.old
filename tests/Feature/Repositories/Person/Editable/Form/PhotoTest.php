<?php

namespace Tests\Feature\Repositories\Person\Editable\Form;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\UploadedPhoto as UploadedPhotoModel;
use App\Repositories\Person\PhotoFileSystem as PhotoFileSystemRepository;
use App\Repositories\Person\Editable\Form\Photo as Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PhotoTest extends TestCase
{
    use PhotoDataProvider;
    use RefreshDatabase;

    public function testCreate(): void
    {
        $repository = new Repository(PhotoFileSystemRepository::instance());
        $this->assertInstanceOf(Repository::class, $repository);
    }

    public function testGetByPerson(): void
    {
        //preparation
        $this->seed();

        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $photoFileSystemRepository = new PhotoFileSystemRepository($disk);

        $repository = new Repository($photoFileSystemRepository);

        $people = PeopleEloquentModel::limit(6)->has("photo")->get();

        $this->createFile($people, $disk->path("photo"));

        //testing
        foreach ($people as $person) {
            $collection = $repository->getByPerson($person->id);
            $this->assertInstanceOf(Collection::class, $collection);
        }

        //clearing
        $this->cleanDirectory($disk);
    }

    public function testUpload(): void
    {
        //preparation
        $this->seed();

        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $photoFileSystemRepository = new PhotoFileSystemRepository($disk);
        $repository = new Repository($photoFileSystemRepository);

        $people = PeopleEloquentModel::limit(6)->get();

        //testing
        foreach ($people as $person) {
            $file = UploadedFile::fake()->create('test.jpg', 100);
            $uploadedPhotoModel = new UploadedPhotoModel($person->id, $file);
            $photoModel = $repository->upload($uploadedPhotoModel);
            $this->assertInstanceOf(PhotoModel::class, $photoModel);
            $this->assertFileExists($disk->path($photoModel->getPathRelative()));
        }
    }
}
