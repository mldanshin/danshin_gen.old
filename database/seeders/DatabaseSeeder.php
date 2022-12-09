<?php

namespace Database\Seeders;

use Database\Seeders\Deploy\GenderSeeder;
use Database\Seeders\Deploy\MarriageRoleGenderSeeder;
use Database\Seeders\Deploy\MarriageRoleScopeSeeder;
use Database\Seeders\Deploy\MarriageRoleSeeder;
use Database\Seeders\Deploy\ParentRoleGenderSeeder;
use Database\Seeders\Deploy\ParentRoleSeeder;
use Database\Seeders\Deploy\UserIdentifierSeeder;
use Database\Seeders\Deploy\UserRoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            GenderSeeder::class,
            MarriageRoleSeeder::class,
            MarriageRoleGenderSeeder::class,
            MarriageRoleScopeSeeder::class,
            ParentRoleSeeder::class,
            ParentRoleGenderSeeder::class,
            UserRoleSeeder::class,
            UserIdentifierSeeder::class,
        ]);
    }
}
