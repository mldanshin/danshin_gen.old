<?php

namespace App\Services;

enum NotificationTypes: int
{
    case EMAIL = 1;
    case PHONE = 2;
    case TELEGRAM = 3;
}
