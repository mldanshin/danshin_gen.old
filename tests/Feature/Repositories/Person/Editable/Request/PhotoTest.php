<?php

namespace Tests\Feature\Repositories\Person\Editable\Request;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Repositories\Person\Editable\Request\Photo as Repository;
use App\Repositories\Person\PhotoFileSystem;
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
        $repository = new Repository(PhotoFileSystem::instance());
        $this->assertInstanceOf(Repository::class, $repository);
    }

    public function testSaveAdding(): void
    {
        //preparation
        $this->seed();
        $this->setConfigFakeDisk();

        $fileSystem = new PhotoFileSystem(Storage::fake("public"));
        $repository = new Repository($fileSystem);

        $this->seedPhoto();

        $people = PeopleEloquentModel::limit(10)->has("photo")->get();

        foreach ($people as $person) {
            $fileTemp = $this->createTempPhotoFile();
            $photoCollection = $this->getPhotoCollection($person->photo()->get(), [$fileTemp], $fileSystem);

            //testing
            $repository->save($person->id, $photoCollection);

            foreach ($person->photo()->get() as $item) {
                $this->assertFileExists(
                    $fileSystem->getPath($person->id, $item->file)
                );
                $this->assertEquals(
                    $item->file,
                    PhotoEloquentModel::where("person_id", $person->id)
                        ->where("file", $item->file)
                        ->value("file"),
                );
            }

            $this->assertFileDoesNotExist(
                $fileSystem->getDisk()->path($fileTemp)
            );
            $this->assertFileExists(
                $fileSystem->getPath($person->id, $fileSystem->getBaseName($fileTemp))
            );
            $this->assertEquals(
                $fileSystem->getBaseName($fileTemp),
                PhotoEloquentModel::where("person_id", $person->id)
                    ->where("file", $fileSystem->getBaseName($fileTemp))
                    ->value("file"),
            );
        }
    }

    public function testSaveRemoving(): void
    {
        //preparation
        $this->seed();

        $fileSystem = new PhotoFileSystem(Storage::fake("public"));
        $repository = new Repository($fileSystem);

        $this->seedPhoto();

        $people = PeopleEloquentModel::limit(10)->has("photo")->get();

        foreach ($people as $person) {
            $photoCollectionBefore = $person->photo()->get();
            $photoCollection = collect();

            //testing
            $repository->save($person->id, $photoCollection);

            foreach ($photoCollectionBefore as $item) {
                $this->assertFileDoesNotExist(
                    $fileSystem->getPath($person->id, $item->file)
                );
            }
            $this->assertNull(PhotoEloquentModel::where("person_id", $person)->value("id"));
        }
    }

    public function testSaveNoChange(): void
    {
        //preparation
        $this->seed();

        $fileSystem = new PhotoFileSystem(Storage::fake("public"));
        $repository = new Repository($fileSystem);

        $this->seedPhoto();

        $people = PeopleEloquentModel::limit(10)->has("photo")->get();

        foreach ($people as $person) {
            $photoCollectionBefore = $person->photo()->get();
            $photoCollection = $this->getPhotoCollection($person->photo()->get(), [], $fileSystem);

            //testing
            $repository->save($person->id, $photoCollection);

            foreach ($photoCollectionBefore as $item) {
                $this->assertFileExists(
                    $fileSystem->getPath($person->id, $item->file)
                );
                $this->assertEquals(
                    $item->file,
                    PhotoEloquentModel::where("person_id", $person->id)
                        ->where("file", $item->file)
                        ->value("file"),
                );
            }
        }
    }

    public function testDelete(): void
    {
        //preparation
        $this->seed();

        $fileSystem = new PhotoFileSystem(Storage::fake("public"));
        $repository = new Repository($fileSystem);

        $this->seedPhoto();

        $people = PeopleEloquentModel::limit(10)->has("photo")->get();

        //testing
        foreach ($people as $person) {
            $this->assertFileExists(
                $fileSystem->getDisk()->path("photo/$person->id")
            );

            $repository->delete($person->id);

            $this->assertFileDoesNotExist(
                $fileSystem->getDisk()->path("photo/$person->id")
            );


            $this->assertNull(PhotoEloquentModel::where("person_id", $person)->value("id"));
        }
    }

    /**
     * @param Collection|PhotoEloquentModel[] $photoEloquent
     * @param array|string[] $filesTemp
     * @return Collection|PhotoModel[]
     */
    private function getPhotoCollection(
        Collection $photoEloquent,
        array $filesTemp,
        PhotoFileSystem $fileSystem
    ): Collection {
        $array = [];
        foreach ($photoEloquent as $item) {
            $array[] = new PhotoModel(
                $fileSystem->getUrl($item->person_id, $item->file),
                $fileSystem->getPathRelative($item->person_id, $item->file),
                $item->_date,
                $item->_order
            );
        }
        foreach ($filesTemp as $item) {
            $array[] = new PhotoModel(
                $fileSystem->getDisk()->url($item),
                $item,
                null,
                12
            );
        }
        return collect($array);
    }
}
