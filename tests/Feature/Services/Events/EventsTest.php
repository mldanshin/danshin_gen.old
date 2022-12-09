<?php

namespace Tests\Feature\Services\Events;

use App\Models\Eloquent\People;
use App\Models\Eloquent\Telegram;
use App\Models\Eloquent\User;
use App\Repositories\Events\Events as Repository;
use App\Services\Events\Events;
use Database\Seeders\Testing\GenderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): Events
    {
        $this->seed();

        $object = new Events(new Repository());

        $this->assertInstanceOf(Events::class, $object);

        return $object;
    }

    public function testSendEventsIsMissing(): void
    {
        $this->seedForEventsIsMissing();

        $object = new Events(new Repository());

        $this->assertTrue($object->send());

        $this->refreshDatabase();
    }

    private function seedForEventsIsMissing(): void
    {
        $this->seed(GenderSeeder::class);

        for ($i = 0; $i < 5; $i++) {
            People::factory()->create([
                "birth_date" => "",
                "death_date" => ""
            ]);
        }
    }

    public function testSendUsersLessSubscription(): void
    {
        $this->seedForSendUsersLessSubscription();

        $object = new Events(new Repository());

        $this->assertTrue($object->send());

        $this->refreshDatabase();
    }

    private function seedForSendUsersLessSubscription(): void
    {
        $this->seed(GenderSeeder::class);

        $day = date("d");
        $month = date("m");

        for ($i = 0; $i < 5; $i++) {
            $person = People::factory()->create([
                "birth_date" => "2000-$month-$day",
                "death_date" => ""
            ]);
            Telegram::create([
                "telegram_id" => uniqid(),
                "person_id" => $person->id
            ]);
            User::create([
                "person_id" => $person->id,
                "password" => Hash::make("password")
            ]);
        }
    }

    public function testSendNotNull(): void
    {
        $this->seedForSendNotNull();

        $object = new Events(new Repository());

        Notification::fake();

        $this->assertTrue($object->send());

        $this->refreshDatabase();
    }

    private function seedForSendNotNull(): void
    {
        $this->seed();

        $day = date("d");
        $month = date("m");

        $people = People::limit(5)->get();

        foreach ($people as $person) {
            $person->birth_date = "2000-$month-$day";
            $person->death_date = "";
            $person->save();
        }
    }
}
