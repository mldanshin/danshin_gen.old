<?php

namespace App\Http\Controllers;

use App\Http\Validate;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderingRequest;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\People\FilterOrdering as FilterOrderingRepository;
use App\Repositories\People\Ordering\Map as OrderingMap;
use App\Repositories\Person\Editable\Form\Person as EditableFormRepository;
use App\Repositories\Person\Readable\Person as ReadableRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class PersonController extends Controller
{
    /**
     * @var mixed[] $data
     */
    private array $data;

    public function __construct(
        PersonShortRepository $personShortRepository,
        FilterOrderingRepository $filterOrderingRepository,
        FilterOrderingRequest $request,
        OrderingMap $orderingMap
    ) {
        $this->data = [
            "people" => $personShortRepository->getCollection(
                $request->people_search,
                $orderingMap->getSorter($request->people_order)
            ),
            "filterOrdering" => $filterOrderingRepository->get(
                $request->people_search,
                $request->people_order
            )
        ];
    }

    public function create(EditableFormRepository $repository): View
    {
        Gate::authorize("editor");

        $this->data["main"] = $repository->getEmpty();
        return view("person-create", $this->data);
    }

    public function show(ReadableRepository $repository, string $id): View
    {
        Validate::personId($id);
        $this->data["main"] = $repository->getById($id);
        return view("person-show", $this->data);
    }

    public function edit(EditableFormRepository $repository, string $id): View
    {
        Gate::authorize("editor");

        Validate::personId($id);
        $this->data["main"] = $repository->getById($id);
        return view("person-edit", $this->data);
    }
}
