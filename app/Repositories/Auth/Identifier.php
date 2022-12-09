<?php

namespace App\Repositories\Auth;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\Email as EmailModel;
use App\Models\Eloquent\Phone as PhoneModel;

final class Identifier
{
    public function getPersonId(UserIdentifierType $identifierType, string $contentIdentifier): ?int
    {
        switch ($identifierType) {
            case UserIdentifierType::EMAIL:
                return EmailModel::where("name", $contentIdentifier)->value("person_id");
            case UserIdentifierType::PHONE:
                return PhoneModel::where("name", $contentIdentifier)->value("person_id");
            default:
                return null;
        }
    }
}
