<?php

namespace Database\Seeders;

use Database\Seeders\Testing\EmailSeeder;
use Database\Seeders\Testing\GenderSeeder;
use Database\Seeders\Testing\MarriageSeeder;
use Database\Seeders\Testing\MarriageRoleGenderSeeder;
use Database\Seeders\Testing\MarriageRoleScopeSeeder;
use Database\Seeders\Testing\MarriageRoleSeeder;
use Database\Seeders\Testing\OldSurnameSeeder;
use Database\Seeders\Testing\ParentChildSeeder;
use Database\Seeders\Testing\ParentRoleGenderSeeder;
use Database\Seeders\Testing\ParentRoleSeeder;
use Database\Seeders\Testing\PeopleSeeder;
use Database\Seeders\Testing\PersonRoleSeeder;
use Database\Seeders\Testing\PhoneSeeder;
use Database\Seeders\Testing\SubscriberEventSeeder;
use Database\Seeders\Testing\TelegramSeeder;
use Database\Seeders\Testing\UserSeeder;
use Database\Seeders\Testing\UserRoleSeeder;
use Database\Seeders\Testing\UserIdentifierSeeder;
use Database\Seeders\Testing\UserUnconfirmedSeeder;
use App\Models\Eloquent\Activity;
use App\Models\Eloquent\Internet;
use App\Models\Eloquent\Photo;
use App\Models\Eloquent\Residence;
use Illuminate\Database\Seeder;

class DatabaseSeederTesting extends Seeder
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
            PeopleSeeder::class,
            OldSurnameSeeder::class,
            ParentChildSeeder::class,
            MarriageSeeder::class,
            UserRoleSeeder::class,
            PersonRoleSeeder::class,
            UserIdentifierSeeder::class,
            PhoneSeeder::class,
            EmailSeeder::class,
            TelegramSeeder::class,
            UserSeeder::class,
            UserUnconfirmedSeeder::class,
            SubscriberEventSeeder::class
        ]);

        Activity::factory()->count(5)->create();
        Internet::factory()->count(13)->create();
        Photo::factory()->count(10)->create();
        Residence::factory()->count(5)->create();
    }
}
