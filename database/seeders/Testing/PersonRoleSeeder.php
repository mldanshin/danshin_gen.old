<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\PersonRole;
use Illuminate\Database\Seeder;

class PersonRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(4, 1);
        $this->create(5, 1);
        $this->create(6, 3);
        $this->create(7, 2);
        $this->create(8, 2);
    }

    private function create(int $person, int $role): void
    {
        PersonRole::create([
            "person_id" => $person,
            "role_id" => $role,
        ]);
    }
}
