<?php

namespace App\Repositories\Download\People;

use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\Download\People\FileSystem as DownloadFileSystem;
use App\Repositories\People\Ordering\Age as AgeOrdering;
use App\Repositories\Person\Readable\Person as PersonRepository;

final class Download
{
    private DownloadFileSystem $downloadFileSystem;
    private PersonRepository $personRepository;

    public function __construct()
    {
        $this->downloadFileSystem = DownloadFileSystem::instance();
        $this->personRepository = new PersonRepository(
            new PersonShortRepository(),
            new AgeOrdering()
        );
    }

    public function getPeople(string $type): string
    {
        $builder = $this->createBuilder($type);
        return $builder->getPeoplePath();
    }

    public function getPerson(string $id, string $type): string
    {
        $builder = $this->createBuilder($type);
        return $builder->getPersonPath($id);
    }

    private function createBuilder(string $type): BuilderAbstract
    {
        switch ($type) {
            case "pdf":
                return new BuilderPdf($this->downloadFileSystem, $this->personRepository);
            default:
                abort(404);
        }
    }
}
