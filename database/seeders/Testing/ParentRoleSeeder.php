<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\ParentRole;
use Illuminate\Database\Seeder;

class ParentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 'undefined');
        $this->create(2, 'father');
        $this->create(3, 'mother');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new ParentRole;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
