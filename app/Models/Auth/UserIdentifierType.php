<?php

namespace App\Models\Auth;

/**
 * The label values must match the values of the id column 
 * table users_identifiers
 */
enum UserIdentifierType: int
{
    case EMAIL = 1;
    case PHONE = 2; 
}
