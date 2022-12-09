<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\UserIdentifier;
use Illuminate\Database\Seeder;

class UserIdentifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 'email');
        $this->create(2, 'phone');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new UserIdentifier;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
