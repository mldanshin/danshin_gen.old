<?php

namespace App\Channels\SmsRu;

final class ValidatorPhoneNumber
{
    public function verifyEmpty(?string $phone): bool
    {
        if (empty($phone)) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyOnlyNumbers(string $phone): bool
    {
        if (!preg_match("#^\d+$#i", $phone)) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyCount(string $phone): bool
    {
        if (strlen($phone) !== config("services.sms_api.number_digits")) {
            return false;
        } else {
            return true;
        }
    }
}
