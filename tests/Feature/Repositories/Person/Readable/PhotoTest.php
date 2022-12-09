<?php

namespace Tests\Feature\Repositories\Person\Readable;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\Person\PhotoFileSystem;
use App\Repositories\Person\Readable\Photo as Repository;
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

    public function testGetByPerson(): void
    {
        //preparation
        $this->seed();

        $disk = Storage::fake("public");
        $this->createDirectory($disk);
        $photoFileSystem = new PhotoFileSystem($disk);

        $repository = new Repository($photoFileSystem);

        $people = PeopleEloquentModel::limit(6)->has("photo")->get();

        $this->createFile($people, $disk->path("photo"));

        //testing
        foreach ($people as $person) {
            $collection = $repository->getByPerson($person->id);
            $this->assertInstanceOf(Collection::class, $collection);
        }

        $people = PeopleEloquentModel::limit(10)->doesntHave("photo")->pluck("id");
        foreach ($people as $person) {
            $collection = $repository->getByPerson($person);
            $this->assertNull($collection);
        }

        //clearing
        $this->cleanDirectory($disk);
    }
}
