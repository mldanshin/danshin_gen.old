<?php

namespace App\Models\Auth\Registration;

use App\Models\Auth\UserIdentifierType;

final class FormRequest
{
    public function __construct(
        private UserIdentifierType $identifierType,
        private string $identifier,
        private string $password
    ) {
    }

    public function getIdentifierType(): UserIdentifierType
    {
        return $this->identifierType;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
