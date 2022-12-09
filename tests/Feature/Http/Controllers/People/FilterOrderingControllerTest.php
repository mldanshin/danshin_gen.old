<?php

namespace Tests\Feature\Http\Controllers\People;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class FilterOrderingControllerTest extends TestCase
{
    use DatabaseMigrations;
    use UserDataProvider;

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($search, $order): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.people.filter_ordering"), [
                "people_search" => $search,
                "people_order" => $order
            ]);
        $response->assertStatus(200);
    }

    public function successProvider()
    {
        return [
            ["text", 2],
            ["bla bla", 1],
            ["", 1],
            [null, 1],
            ["text", null],
        ];
    }

    /**
     * @dataProvider wrongProvider
     */
    public function testWrong($search, $order): void
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.people.filter_ordering"), [
                "people_search" => $search,
                "people_order" => $order
            ]);
        $response->assertStatus(302);
    }

    public function wrongProvider()
    {
        return [
            ["text", "ddd"],
            ["bla bla", "qqq"],
            ["bla bla", 0],
            ["bla bla", -1]
        ];
    }
}
