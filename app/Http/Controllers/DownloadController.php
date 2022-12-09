<?php

namespace App\Http\Controllers;

use App\Http\Validate;
use App\Repositories\Download\DataBase\Download as DataBase;
use App\Repositories\Download\Tree\Tree;
use App\Repositories\Download\People\Download as People;
use App\Repositories\Download\Photo\Download as Photo;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class DownloadController extends Controller
{
    public function downloadPeople(People $repository, string $type): BinaryFileResponse
    {
        return response()->download($repository->getPeople($type));
    }

    public function downloadPerson(People $repository, string $id, string $type): BinaryFileResponse
    {
        Validate::personId($id);
        return response()->download($repository->getPerson($id, $type));
    }

    public function downloadTree(string $id, ?string $parentId = null): BinaryFileResponse
    {
        Validate::personId($id);
        Validate::parent($id, $parentId);

        $repository = new Tree();
        return response()->download($repository->createFile($id, $parentId));
    }

    public function downloadDataBase(): BinaryFileResponse
    {
        $repository = new DataBase();
        return response()->download($repository->getPath());
    }

    public function downloadPhoto(): BinaryFileResponse|Response
    {
        $repository = new Photo();
        $path = $repository->getPath();

        if ($path === null) {
            return response(__("download.message.photo_missing"));
        } else {
            return response()->download($path);
        }
    }
}
