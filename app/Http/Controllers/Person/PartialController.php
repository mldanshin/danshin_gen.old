<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Http\Requests\Person\Partials\MarriageRequest;
use App\Http\Requests\Person\Partials\ParentRequest;
use App\Http\Requests\Person\Partials\PhotoRequest;
use App\Repositories\Person\PhotoFileSystem;
use App\Repositories\Person\Editable\Form\Person as PersonRepository;
use App\Repositories\Person\Editable\Form\Photo as PhotoRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class PartialController extends Controller
{
    public function getListInput(string $name): View
    {
        Gate::authorize("editor");

        return view($name);
    }

    public function getParent(ParentRequest $request, PersonRepository $repository): View
    {
        Gate::authorize("editor");

        return view("partials.person.partials.editor.parent", [
            "item" => $repository->getParentEmpty($request->person_id, $request->parent_role)
        ]);
    }

    public function getMarriage(MarriageRequest $request, PersonRepository $repository): View
    {
        Gate::authorize("editor");

        return view("partials.person.partials.editor.marriage", [
            "item" => $repository->getMarriageEmpty(
                $request->person_id,
                $request->gender_id,
                $request->role_soulmate
            )
        ]);
    }

    public function getPhoto(PhotoRequest $request): View
    {
        Gate::authorize("editor");

        $repository = new PhotoRepository(PhotoFileSystem::instance());
        return view("partials.person.partials.editor.photo", [
            "item" => $repository->upload($request->getModel())
        ]);
    }
}
