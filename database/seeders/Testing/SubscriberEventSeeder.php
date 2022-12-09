<?php

namespace Database\Seeders\Testing;

use App\Models\Eloquent\SubscriberEvent;
use Illuminate\Database\Seeder;

class SubscriberEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 1, 1);
        $this->create(2, 2, 3);
    }

    private function create(int $id, int $userId, int $telegramId): void
    {
        $obj = new SubscriberEvent();
        $obj->id = $id;
        $obj->user_id = $userId;
        $obj->telegram_id = $telegramId;
        $obj->save();
    }
}
