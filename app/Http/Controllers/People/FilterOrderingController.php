<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderingRequest;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\People\Ordering\Map as OrderingMap;
use Illuminate\View\View;

final class FilterOrderingController extends Controller
{
    public function __invoke(
        FilterOrderingRequest $request,
        PersonShortRepository $repository,
        OrderingMap $orderingMap
    ): View {
        $people = $repository->getCollection(
            $request->input("people_search"),
            $orderingMap->getSorter($request->input("people_order"))
        );
        return view("partials.people.partials.list", [
            "people" => $people
            ]);
    }
}
