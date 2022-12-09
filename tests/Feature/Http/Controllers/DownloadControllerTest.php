<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class DownloadControllerTest extends TestCase
{
    use PeopleDataProvider;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider peopleSuccessProvider
     */
    public function testPeopleSuccess(string $type): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("download.people", [$type]));
        $response->assertStatus(200);
    }

    public function peopleSuccessProvider(): array
    {
        return [
            ["pdf"],
        ];
    }

    /**
     * @dataProvider peopleWrongProvider
     */
    public function testPeopleWrong(string $type): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("download.people", [$type]));
        $response->assertStatus(404);
    }

    public function peopleWrongProvider(): array
    {
        return [
            ["pdf1"],
            ["word3"],
            ["odt23"],
        ];
    }

    public function testPersonSuccess(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $type = "pdf";

        $people = PeopleEloquentModel::limit(10)->pluck("id");
        foreach ($people as $person) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("download.person", [$person, $type]));
            $response->assertStatus(200);
        }
    }

    /**
     * @dataProvider personWrongProvider
     */
    public function testPersonWrong(string $id, string $type): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("download.person", [$id, $type]));
        $response->assertStatus(404);
    }

    public function personWrongProvider(): array
    {
        return [
            ["bla", "pdf"],
            ["1", "bla"],
        ];
    }

    public function testTreeSuccess(): void
    {
        $this->seed();

        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("download.tree", [
                    $person->id,
                    $this->randomParent($person)
            ]));
            $response->assertStatus(200);
        }
    }

    public function testTreeWrong(): void
    {
        $this->seed();

        $idWrong = $this->peopleIdWrong();

        foreach ($idWrong as $id) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->get(route("download.tree", [$id]));
            $response->assertStatus(404);
        }
    }

    public function testDataBase(): void
    {
        $this->markTestSkipped(
            "Тест пропущен так как тестирование идёт через sqlite в памяти, дамп которого не смог получить,
                а писать в рабочем коде получение дампа только из-за теста считаю лишним"
        );
    }

    public function testPhoto(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("download.photo"));
        $response->assertStatus(200);
    }

    public function testPhotoEmpty(): void
    {
        $this->seed();
        $this->setConfigFakeDisk();
        $this->cleanDirectory();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("download.photo"));
        $response->assertStatus(200);
        $response->assertSee(__("download.message.photo_missing"));
    }
}
