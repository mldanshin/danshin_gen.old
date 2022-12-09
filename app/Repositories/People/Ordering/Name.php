<?php

namespace App\Repositories\People\Ordering;

use App\Models\PersonShort as PersonModel;

final class Name implements OrderingContract
{
    use MultiSort;

    /**
     * @param PersonModel[] $person
     */
    public function sort(array &$person): void
    {
        $callback = $this->createCallbackCompare(["getSurname", "getName", "getPatronymic"]);
        usort($person, $callback);
    }
}
