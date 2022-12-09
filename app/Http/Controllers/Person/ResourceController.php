<?php

namespace App\Http\Controllers\Person;

use App\Http\Validate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Person\EditableRequest;
use App\Repositories\Person\Editable\Form\Person as EditableFormRepository;
use App\Repositories\Person\Editable\Request\Person as EditableRequestRepository;
use App\Repositories\Person\Readable\Person as ReadableRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class ResourceController extends Controller
{
    public function create(EditableFormRepository $repository, Request $request): View
    {
        Gate::authorize("editor");

        return view("partials.person.create", [
            "model" => $repository->getEmpty()
        ]);
    }

    public function store(
        EditableRequest $request,
        EditableRequestRepository $repositoryEditable,
        ReadableRepository $repositoryReadable
    ): JsonResponse {
        Gate::authorize("editor");

        $personId = $repositoryEditable->store($request->getPerson());
        return response()->json([
            "message" => __("person.crud.message.ok.store"),
            "body" => view("partials.person.show", [
                "model" => $repositoryReadable->getById($personId)
            ])->render()
        ]);
    }

    public function show(ReadableRepository $repository, string $id): View
    {
        Validate::personId($id);
        return view("partials.person.show", [
            "model" => $repository->getById($id)
        ]);
    }

    public function edit(EditableFormRepository $repository, string $id): View
    {
        Gate::authorize("editor");

        Validate::personId($id);
        return view("partials.person.edit", [
            "model" => $repository->getById($id)
        ]);
    }

    public function update(
        EditableRequest $request,
        EditableRequestRepository $repositoryEditable,
        ReadableRepository $repositoryReadable
    ): JsonResponse {
        Gate::authorize("editor");

        $personId = $repositoryEditable->update($request->getPerson());
        return response()->json([
            "message" => __("person.crud.message.ok.save"),
            "body" => view("partials.person.show", [
                "model" => $repositoryReadable->getById($personId)
            ])->render()
        ]);
    }

    public function destroy(EditableRequestRepository $repository, string $id): JsonResponse
    {
        Gate::authorize("editor");

        Validate::personId($id);
        $repository->delete($id);
        return response()->json([
            "message" => __("person.crud.message.ok.destroy"),
            "body" => view("partials.person.index")->render()
        ]);
    }

    public function close(): View
    {
        return view("partials.person.index");
    }
}
