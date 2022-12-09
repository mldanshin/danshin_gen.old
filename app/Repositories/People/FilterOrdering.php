<?php

namespace App\Repositories\People;

use App\Models\People\FilterOrdering as FilterOrderingModel;
use App\Repositories\People\Ordering\Map as OrderingMap;

final class FilterOrdering
{
    public function __construct(
        private OrderingMap $orderingMap
    ) {
    }

    public function get(?string $search, ?int $orderingCurrent): FilterOrderingModel
    {
        $search = ($search === null) ? "" : $search;
        $orderingCurrent = ($orderingCurrent === null) ? $this->orderingMap->getKeyDefault() : $orderingCurrent;

        return new FilterOrderingModel($search, $this->orderingMap->get(), $orderingCurrent);
    }
}
