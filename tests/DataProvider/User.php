<?php

namespace Tests\DataProvider;

use App\Models\Eloquent\User as UserModel;

trait User
{
    private function getAdmim(): UserModel
    {
        return UserModel::find(1);
    }
}