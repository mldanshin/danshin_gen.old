<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderingRequest;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\Events\Events as EventsRepository;
use App\Repositories\People\FilterOrdering as FilterOrderingRepository;
use App\Repositories\People\Ordering\Map as OrderingMap;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class EventController extends Controller
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

    public function show(EventsRepository $repository): View
    {
        $this->data["main"] = $repository->get();
        $this->data["user"] = Auth::user();
        return view("events", $this->data);
    }
}
