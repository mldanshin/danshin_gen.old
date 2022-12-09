<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\People\FilterOrderingRequest;
use App\Repositories\PersonShort as PersonShortRepository;
use App\Repositories\Events\Subscription as Repository;
use App\Repositories\People\FilterOrdering as FilterOrderingRepository;
use App\Repositories\People\Ordering\Map as OrderingMap;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class EventSubscriptionController extends Controller
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

    public function create(): View|RedirectResponse
    {
        $user = Auth::user();
        $repository = new Repository($user);

        if ($repository->isSubscription()) {
            return redirect()->route("events.subscription.edit");
        } else {
            $this->data["userId"] = $user->id;
            $this->data["code"] = $repository->generateConfirmCode();
            return view("events-subscription-create", $this->data);
        }
    }

    public function edit(): View|RedirectResponse
    {
        $user = Auth::user();
        $repository = new Repository($user);

        if ($repository->isSubscription()) {
            $this->data["user"] = $user;
            return view("events-subscription-edit", $this->data);
        } else {
            return redirect()->route("events.subscription.create");
        }
    }
}
