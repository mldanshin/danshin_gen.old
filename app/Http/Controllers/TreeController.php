<?php

namespace App\Http\Controllers;

use App\Http\Validate;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderingRequest;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\People\FilterOrdering as FilterOrderingRepository;
use App\Repositories\People\Ordering\Map as OrderingMap;
use App\Repositories\Tree\Tree as Repository;
use App\View\Tree\Tree as TreeView;
use Illuminate\View\View;

final class TreeController extends Controller
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

    public function show(string $id, ?string $parentId = null): View
    {
        Validate::personId($id);
        Validate::parent($id, $parentId);

        $this->data["personId"] = $id;
        $this->data["parentId"] = $parentId;
        return view("tree", $this->data);
    }

    public function showImage(string $id, ?string $parentId = null): View
    {
        Validate::personId($id);
        Validate::parent($id, $parentId);

        $repository = new Repository($id, $parentId);
        return view("partials.tree.tree", [
            "tree" => new TreeView($repository->get(), hasLinks: false)
        ]);
    }
}
