<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 5, "secret1");
        $this->create(2, 6, "secret2");
        $this->create(3, 7, "secret3");
        $this->create(4, 8, "secret4");
        $this->create(5, 13, "secret4");
    }

    private function create(int $id, int $person_id, string $password): void
    {
        $user = new User();
        $user->id = $id;
        $user->person_id = $person_id;
        $user->password = Hash::make($password);
        $user->save();
    }
}
