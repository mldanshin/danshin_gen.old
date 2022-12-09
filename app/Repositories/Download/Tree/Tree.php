<?php

namespace App\Repositories\Download\Tree;

use App\Repositories\Download\Tree\FileSystem;
use App\Repositories\Tree\Tree as TreeRepository;
use App\View\Tree\Tree as TreeView;

final class Tree
{
    private FileSystem $fileSystem;

    public function __construct()
    {
        $this->fileSystem = FileSystem::instance();
    }

    public function createFile(string $id, ?string $parentId): string
    {
        $treeModel = (new TreeRepository($id, $parentId))->get();
        $content = view(
            "partials.tree.tree",
            ["tree" => new TreeView($treeModel, hasLinks: false)]
        )->render();
        return $this->fileSystem->createFile($id, $parentId, $content);
    }
}
