<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\People;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 0, 2, "Danshin", "Pavel", "Tikhonovich", "1905-10-11", "Novosibirsk", "1986-02-04", "Kemerovo", null);
        $this->create(2, 0, 3, "Danshina", "Elizabeth", "Dmitrievna", "1909-09-04", "Novosibirsk", "1995-12-14", "Kemerovo", null);
        $this->create(3, 0, 2, "Danshin", "Leonid", "Pavlovich", "1950-01-23", "Kemerovo", null, null, null);
        $this->create(4, 0, 3, "Danshina", "Tatyana", "Ivanovna", "1952-09-17", "Kemerovo", "2021-01-06", "Kemerovo", null);
        $this->create(5, 0, 2, "Danshin", "Maxim", "Leonidovich", "1979-11-18", "Kemerovo", null, null, null);
        $this->create(6, 0, 3, "Burkina", "Natalia", "Vladimirovna", "1988-01-18", "Kemerovo", null, null, null);
        $this->create(7, 0, 2, "Danshin", "Denis", "Maksimovich", "2014-06-08", "Kemerovo", null, null, null);
        $this->create(8, 0, 2, "Danshin", "Egor", "Leonidovich", "1981-04-13", "Kemerovo", null, null, null);
        $this->create(9, 0, 3, "Solovyova", "Oksana", "Leonidovna", "1981-04-13", "Kemerovo", null, null, null);
        $this->create(10, 0, 2, "Solovyov", "Igor", "Ivanovich", "1964-08-08", "Kemerovo", null, null, null);
        $this->create(11, 0, 3, "Solovyova", "Olga", "Igorevna", "2006-09-11", "Kemerovo", null, null, null);
        $this->create(12, 0, 3, "Solovyov", "Oleg", "Igorevich", "2012-09-18", "Kemerovo", null, null, null);
        $this->create(13, 0, 3, "Petrenko", "Elena", "Sergeevna", "1980-07-08", "Moskva", null, null, null);
        $this->create(14, 0, 3, "Petrenko", "Nina", "Sergeevna", "1982-03-29", "Moskva", null, null, null);
        $this->create(15, 0, 3, "Petrenko", "Olga", "Sergeevna", "1984-11-09", "Moskva", null, null, null);
        $this->create(16, 0, 2, "Sidorov", "Maxim", "Petrovich", "1999-10-12", "Kemerovo", null, null, null);
        $this->create(17, 0, 2, "Sidorov", "Denis", "Petrovich", "2000-03-19", "Kemerovo", null, null, null);
        $this->create(18, 0, 2, "Sidorov", "Igor", "Petrovich", "2002-01-31", "Kemerovo", null, null, null);
    }

    private function create(
        int $id,
        int $isUnavailable,
        int $genderId,
        string $surname,
        string $name,
        ?string $patronymic,
        string $birthDate,
        string $birthPlace,
        ?string $deathDate,
        ?string $burialPlace,
        ?string $note
    ): void {
        $obj = new People();
        $obj->id = $id;
        $obj->is_unavailable = $isUnavailable;
        $obj->gender_id = $genderId;
        $obj->surname = $surname;
        $obj->name = $name;
        $obj->patronymic = $patronymic;
        $obj->birth_date = $birthDate;
        $obj->birth_place = $birthPlace;
        $obj->death_date = $deathDate;
        $obj->burial_place = $burialPlace;
        $obj->note = $note;
        $obj->save();
    }
}
