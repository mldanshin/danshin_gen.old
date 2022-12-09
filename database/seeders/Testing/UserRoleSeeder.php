<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\UserRole;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 'admin');
        $this->create(2, 'vip');
        $this->create(3, 'ordinary');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new UserRole;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
