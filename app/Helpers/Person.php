<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

final class Person
{
    public static function gender(int $id): string
    {
        return __("db.gender.$id");
    }

    public static function surname(string $value): string
    {
        if ($value === "") {
            return __("person.surname.null");
        } else {
            return $value;
        }
    }

    /**
     * @param Collection|string[]|null $collection
     */
    public static function oldSurname(?Collection $collection): string
    {
        if ($collection !== null && $collection->count() > 0) {
            return "(" . implode(",", $collection->all()) . ")";
        } else {
            return "";
        }
    }

    public static function name(string $value): string
    {
        if ($value === "") {
            return __("person.name.null");
        } else {
            return $value;
        }
    }

    public static function patronymic(?string $value): string
    {
        if ($value === null) {
            return "";
        } elseif ($value === "") {
            return __("person.patronymic.null");
        } else {
            return $value;
        }
    }

    public static function patronymicEdit(?string $value): string
    {
        if ($value === null) {
            return "!";
        } elseif ($value === "") {
            return "";
        } else {
            return $value;
        }
    }

    public static function marriage(int $id): string
    {
        return __("db.marriage_role.$id");
    }

    public static function parent(int $id): string
    {
        return __("db.parent_role.$id");
    }
}
