<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\OldSurname;
use Illuminate\Database\Seeder;

class OldSurnameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 2, "Petrova", 1);
        $this->create(2, 4, "Pluta", 1);
        $this->create(3, 9, "Danshin", 1);
    }

    private function create(
        int $id,
        int $personId,
        string $surname,
        int $order
    ): void {
        $obj = new OldSurname();
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->surname = $surname;
        $obj->_order = $order;
        $obj->save();
    }
}
