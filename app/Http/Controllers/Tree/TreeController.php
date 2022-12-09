<?php

namespace App\Http\Controllers\Tree;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tree\SvgRequest;
use App\Repositories\Tree\Tree as Repository;
use App\View\Tree\Tree as TreeView;
use Illuminate\View\View;

final class TreeController extends Controller
{
    public function index(SvgRequest $request): View
    {
        $repository = new Repository($request->person_id, $request->parent_id);
        return view("partials.tree.index", [
            "toggle" => $repository->getToggle(),
            "tree" => new TreeView($repository->get(), $request->width_screen, $request->height_screen)
        ]);
    }

    public function show(SvgRequest $request): View
    {
        $repository = new Repository($request->person_id, $request->parent_id);
        return view("partials.tree.tree", [
            "tree" => new TreeView($repository->get(), $request->width_screen, $request->height_screen)
        ]);
    }
}
