<?php

namespace Tests\Feature\Repositories\Download\People;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\Download\People\BuilderPdf as BuilderPdfRepository;
use App\Repositories\Download\People\FileSystem;
use App\Repositories\People\Ordering\Age as AgeOrdering;
use App\Repositories\Person\Readable\Person as PersonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class BuilderPdfTest extends TestCase
{
    use PhotoDataProvider;
    use RefreshDatabase;

    public function testCreate(): void
    {
        $personRepository = new PersonRepository(
            new PersonShortRepository(),
            new AgeOrdering()
        );
        $repository = new BuilderPdfRepository(FileSystem::instance(), $personRepository);
        $this->assertInstanceOf(BuilderPdfRepository::class, $repository);
    }

    public function testGetPeoplePath(): void
    {
        //preparation
        $this->seed();
        $this->setConfigFakeDisk();

        $disk = Storage::fake("public");
        $this->seedPhoto($disk);

        $fileSystem = new FileSystem($disk);
        $personRepository = new PersonRepository(
            new PersonShortRepository(),
            new AgeOrdering()
        );
        $repository = new BuilderPdfRepository($fileSystem, $personRepository);

        //execution
        $path = $repository->getPeoplePath();
        $this->assertFileExists($path);
    }

    public function testGetPersonPath(): void
    {
        //preparation
        $this->seed();
        $this->setConfigFakeDisk();

        $disk = Storage::fake("public");
        $this->seedPhoto($disk);

        $fileSystem = new FileSystem($disk);
        $personRepository = new PersonRepository(
            new PersonShortRepository(),
            new AgeOrdering()
        );
        $repository = new BuilderPdfRepository($fileSystem, $personRepository);

        //execution
        $people = PeopleEloquentModel::limit(10)->pluck("id");
        foreach ($people as $person) {
            $path = $repository->getPersonPath($person);
            $this->assertFileExists($path);
        }
    }
}
