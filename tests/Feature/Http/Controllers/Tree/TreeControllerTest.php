<?php

namespace Tests\Feature\Http\Controllers\Tree;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DataProvider\People as PeopleDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class TreeControllerTest extends TestCase
{
    use PeopleDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider providerIndexSuccess
     */
    public function testIndexSuccess(int $personId, ?int $parentId): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(
                route("partials.tree.index"),
                [
                    "person_id" => (string)$personId,
                    "parent_id" => $parentId,
                    "width_screen" => 1280,
                    "height_screen" => 720
                ]
            );
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerIndexSuccess(): array
    {
        return [
            [1, null],
            [3, null],
            [3, 1],
            [5, 3],
        ];
    }

    /**
     * @dataProvider providerShowSuccess
     */
    public function testShowSuccess(int $personId, ?int $parentId): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(
                route("partials.tree.show"),
                [
                    "person_id" => (string)$personId,
                    "parent_id" => $parentId,
                    "width_screen" => 1280,
                    "height_screen" => 720
                ]
            );
        $response->assertStatus(200);
    }

    /**
     * @return array[]
     */
    public function providerShowSuccess(): array
    {
        return [
            [1, null],
            [3, null],
            [3, 1],
            [5, 3],
        ];
    }

    /**
     * @dataProvider providerIndexWrong
     */
    public function testIndexWrong(?string $personId, ?string $parentId): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
        ->post(
            route("partials.tree.index"),
            [
                "person_id" => (string)$personId,
                "parent_id" => $parentId,
                "width_screen" => 1280,
                "height_screen" => 720
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerIndexWrong(): array
    {
        return [
            ["-19", null], //invalid person_id
            [null, null], //empty person_id
            ["", null], //empty person_id
            ["1", "2"], //relation person and parent wrong
            ["1", "3"], //relation person and parent wrong
        ];
    }

    /**
     * @dataProvider providerShowWrong
     */
    public function testShowWrong(?string $personId, ?string $parentId): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
        ->post(
            route("partials.tree.show"),
            [
                "person_id" => (string)$personId,
                "parent_id" => $parentId,
                "width_screen" => 1280,
                "height_screen" => 720
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerShowWrong(): array
    {
        return [
            ["-19", null], //invalid person_id
            [null, null], //empty person_id
            ["", null], //empty person_id
            ["1", "2"], //relation person and parent wrong
            ["1", "3"], //relation person and parent wrong
        ];
    }

    private function testWrongParent(string $nameRoute): void
    {
        $people = PeopleEloquentModel::limit(5)->get();

        foreach ($people as $person) {
            $wrongParent = $this->randomExceptParent($person->id);
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(
                    route($nameRoute),
                    [
                        "person_id" => (string)$person->id,
                        "parent_id" => $wrongParent,
                        "width_screen" => 1280,
                        "height_screen" => 720
                    ]
                );
            $response->assertStatus(302);
        }
    }

    /**
     * @dataProvider providerWrongScreen
     */
    public function testWrongScreen(?int $width, ?int $height): void
    {
        $this->seed();

        $person = PeopleEloquentModel::get()->random();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(
                route("partials.tree.show"),
                [
                    "person_id" => (string)$person->id,
                    "width_screen" => $width,
                    "height_screen" => $height
                ]
            );
        $response->assertStatus(302);
    }

    /**
     * @return array[]
     */
    public function providerWrongScreen(): array
    {
        return [
            [1200, null],
            [null, null],
            [null, 720],
            [1200, 20],
            [10, 720],
            [10, 20],
            [-1200, -720],
        ];
    }
}
