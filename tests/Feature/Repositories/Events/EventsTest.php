<?php

namespace Tests\Feature\Repositories\Events;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Events\Events as EventsModel;
use App\Models\Events\Birth as BirthModel;
use App\Models\Events\BirthWould as BirthWouldModel;
use App\Models\Events\Death as DeathModel;
use App\Models\Events\Person as PersonModel;
use App\Repositories\Events\Events as EventsRepository;
use Database\Seeders\Testing\GenderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class EventsTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateSuccess(): void
    {
        $this->seedPeople();

        $repository = new EventsRepository();
        $this->assertInstanceOf(EventsRepository::class, $repository);

        $events = $repository->get();
        $this->assertInstanceOf(EventsModel::class, $events);

        $past = $events->getPast();
        $this->assertEquals(1, $past->count());
        $this->assertInstanceOf(DeathModel::class, $past[0]);
        $this->assertEquals(new PersonModel(1, "Ivanov1", "Ivan1", "Ivanovich1"), $past[0]->getPerson());

        $today = $events->getToday();
        $this->assertEquals(3, $today->count());
        $this->assertInstanceOf(BirthModel::class, $today[0]);
        $this->assertEquals(new PersonModel(5, "Ivanov5", "Ivan5", "Ivanovich5"), $today[0]->getPerson());
        $this->assertInstanceOf(BirthWouldModel::class, $today[1]);
        $this->assertEquals(new PersonModel(7, "Ivanov7", "Ivan7", "Ivanovich7"), $today[1]->getPerson());
        $this->assertInstanceOf(DeathModel::class, $today[2]);
        $this->assertEquals(new PersonModel(6, "Ivanov6", "Ivan6", "Ivanovich6"), $today[2]->getPerson());

        $nearest = $events->getNearest();
        $this->assertEquals(2, $nearest->count());
        $this->assertInstanceOf(BirthModel::class, $nearest[0]);
        $this->assertEquals(new PersonModel(8, "Ivanov8", "Ivan8", "Ivanovich8"), $nearest[0]->getPerson());
        $this->assertInstanceOf(DeathModel::class, $nearest[1]);
        $this->assertEquals(new PersonModel(9, "Ivanov9", "Ivan9", "Ivanovich9"), $nearest[1]->getPerson());
    }

    private function seedPeople(): void
    {
        (new GenderSeeder())->run();
        //past
        PeopleEloquentModel::factory()->create([
            "id" => 1,
            "is_unavailable" => 0,
            "surname" => "Ivanov1",
            "name" => "Ivan1",
            "patronymic" => "Ivanovich1",
            "birth_date" => "????-01-01",
            "death_date" => (new \DateTime())->sub(new \DateInterval("P1Y1D"))->format("Y-m-d")
        ]);

        //do not choose
        PeopleEloquentModel::factory()->create([
            "id" => 2,
            "is_unavailable" => 0,
            "surname" => "Ivanov2",
            "name" => "Ivan2",
            "patronymic" => "Ivanovich2",
            "birth_date" => "20??-01-01",
            "death_date" => "20?0-10-09"
        ]);
        PeopleEloquentModel::factory()->create([
            "id" => 3,
            "is_unavailable" => 0,
            "surname" => "Ivanov3",
            "name" => "Ivan3",
            "patronymic" => "Ivanovich3",
            "birth_date" => "",
            "death_date" => null
        ]);
        PeopleEloquentModel::factory()->create([
            "id" => 4,
            "is_unavailable" => 0,
            "surname" => "Ivanov4",
            "name" => "Ivan4",
            "patronymic" => "Ivanovich4",
            "birth_date" => "",
            "death_date" => "20?0-10-09"
        ]);

        //today
        PeopleEloquentModel::factory()->create([
            "id" => 5,
            "is_unavailable" => 0,
            "surname" => "Ivanov5",
            "name" => "Ivan5",
            "patronymic" => "Ivanovich5",
            "birth_date" => "2004-" . (new \DateTime())->format("m-d"),
            "death_date" => null
        ]);
        PeopleEloquentModel::factory()->create([
            "id" => 6,
            "is_unavailable" => 0,
            "surname" => "Ivanov6",
            "name" => "Ivan6",
            "patronymic" => "Ivanovich6",
            "birth_date" => "",
            "death_date" => "2021-" . (new \DateTime())->format("m-d"),
        ]);
        PeopleEloquentModel::factory()->create([
            "id" => 7,
            "is_unavailable" => 0,
            "surname" => "Ivanov7",
            "name" => "Ivan7",
            "patronymic" => "Ivanovich7",
            "birth_date" => "2004-" . (new \DateTime())->format("m-d"),
            "death_date" => "2010-??-01",
        ]);

        //nearest
        PeopleEloquentModel::factory()->create([
            "id" => 8,
            "is_unavailable" => 0,
            "surname" => "Ivanov8",
            "name" => "Ivan8",
            "patronymic" => "Ivanovich8",
            "birth_date" => "2000-" . (new \DateTime())->add(new \DateInterval("P1D"))->format("m-d"),
            "death_date" => null
        ]);
        PeopleEloquentModel::factory()->create([
            "id" => 9,
            "is_unavailable" => 0,
            "surname" => "Ivanov9",
            "name" => "Ivan9",
            "patronymic" => "Ivanovich9",
            "birth_date" => "2000-??-10",
            "death_date" => "2020-" . (new \DateTime())->add(new \DateInterval("P2D"))->format("m-d"),
        ]);
    }
}
