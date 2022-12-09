<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\Telegram;
use Illuminate\Database\Seeder;

class TelegramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 5, "a123", "@mldanshin");
        $this->create(2, 5, "b4568", "@mldanshin2");
        $this->create(3, 6, "c984", null);
        $this->create(4, 13, "d689", null);
    }

    private function create(int $id, int $personId, string $telegramId, ?string $telegramUsername): void
    {
        $obj = new Telegram();
        $obj->id = $id;
        $obj->person_id = $personId;
        $obj->telegram_id = $telegramId;
        $obj->telegram_username = $telegramUsername;
        $obj->save();
    }
}
