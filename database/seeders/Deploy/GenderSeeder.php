<?php

namespace Database\Seeders\Deploy;

use App\Models\Eloquent\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 'unknown');
        $this->create(2, 'man');
        $this->create(3, 'woman');
    }

    private function create(int $id, string $slug): void
    {
        $obj = new Gender;
        $obj->id = $id;
        $obj->slug = $slug;
        $obj->save();
    }
}
