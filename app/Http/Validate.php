<?php

namespace App\Http;

use App\Models\Eloquent\People;
use App\Models\Eloquent\User;
use App\Exceptions\NotFoundException;

final class Validate
{
    /**
    * @throws NotFoundException
    */
    public static function personId(string $id): bool
    {
        $person = People::where("id", $id)->exists();
        if ($person === false) {
            throw new NotFoundException("The person with id number = $id does not exist");
        }

        return true;
    }

    /**
    * @throws NotFoundException
    */
    public static function parent(string $id, ?string $parentId): bool
    {
        if ($parentId !== null) {
            $parents = People::find($id)->parents()->get();
            if ($parents->isEmpty()) {
                throw new NotFoundException("The person - parent with id number = $id does not exist");
            } else {
                $res = $parents->where("parent_id", $parentId);
                if ($res->isEmpty()) {
                    throw new NotFoundException("The person - parent with id number = $id does not exist");
                }
            }
        }

        return true;
    }
}
