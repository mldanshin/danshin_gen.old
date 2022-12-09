<?php

namespace Tests\Feature\Repositories\Download\Tree;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\Download\Tree\Tree as TreeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\DataProvider\Storage as StorageDataProvider;

final class TreeTest extends TestCase
{
    use RefreshDatabase;
    use StorageDataProvider;

    public function testCreate(): void
    {
        $repository = new TreeRepository();
        $this->assertInstanceOf(TreeRepository::class, $repository);
    }

    public function testCreateFile(): void
    {
        //preparation
        $this->seed();
        $this->setConfigFakeDisk();

        $directoryPath = Storage::fake("public")->path("download");
        File::cleanDirectory($directoryPath);

        $repository = new TreeRepository();

        //execution
        $people = PeopleEloquentModel::limit(10)->pluck("id");
        foreach ($people as $person) {
            $path = $repository->createFile($person, null);
            $expectedPath = $directoryPath . "/tree_$person.svg";
            $this->assertEquals($expectedPath, $path);
            $this->assertTrue(File::exists($path));
        }

        //clearing
        File::cleanDirectory($directoryPath);
    }
}
