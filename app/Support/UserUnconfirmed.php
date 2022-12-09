<?php

namespace App\Support;

use App\Models\Eloquent\UserUnconfirmed as UserUnconfirmedModel;

final class UserUnconfirmed
{
    public function delete(): mixed
    {
        $timestampActual = time() - config("auth.confirmation_user.user_obsolescence_interval");
        return UserUnconfirmedModel::where("timestamp", "<", $timestampActual)->delete();
    }
}
