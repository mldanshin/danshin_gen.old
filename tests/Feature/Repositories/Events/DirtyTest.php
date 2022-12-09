<?php

namespace Tests\Feature\Repositories\Events;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\Events\Dirty as DirtyRepository;
use Database\Seeders\Testing\GenderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DirtyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerForTestCreateSuccess
     */
    public function testCreateSuccess(int $pastDay, \DateTime $today, int $nearestDay): void
    {
        $this->seed();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );
        $this->assertInstanceOf(DirtyRepository::class, $repository);
    }

    public function providerForTestCreateSuccess(): array
    {
        return [
            [3, new \DateTime(), 3],
            [30, new \DateTime(), 30],
        ];
    }

    /**
     * @dataProvider providerForTestCreateWrong
     */
    public function testCreateWrong(int $pastDay, \DateTime $today, int $nearestDay): void
    {
        $this->seed();

        $this->expectException(\Exception::class);

        new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );
    }

    public function providerForTestCreateWrong(): array
    {
        return [
            [-3, new \DateTime(), 3],
            [35, new \DateTime(), 30],
            [3, new \DateTime(), 0],
        ];
    }

    /**
     * @dataProvider providerForTestPastBirth
     */
    public function testPastBirth(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getPastBirth()->count());
    }

    public function providerForTestPastBirth(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                3,
                1
            ],
            [
                7,
                new \DateTime("2021-10-15"),
                3,
                2
            ],
            [
                7,
                new \DateTime("2021-01-02"),
                3,
                1
            ]
        ];
    }

    /**
     * @dataProvider providerForTestPastDeath
     */
    public function testPastDeath(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getPastDeath()->count());
    }

    public function providerForTestPastDeath(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                3,
                1
            ],
            [
                7,
                new \DateTime("2021-10-15"),
                3,
                3
            ],
            [
                7,
                new \DateTime("2021-01-02"),
                3,
                2
            ]
        ];
    }

    /**
     * @dataProvider providerForTestTodayBirth
     */
    public function testTodayBirth(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getTodayBirth()->count());
    }

    public function providerForTestTodayBirth(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                3,
                2
            ],
            [
                7,
                new \DateTime("2021-10-10"),
                3,
                0
            ],
            [
                7,
                new \DateTime("2021-01-02"),
                3,
                0
            ]
        ];
    }

    /**
     * @dataProvider providerForTestTodayDeath
     */
    public function testTodayDeath(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getTodayDeath()->count());
    }

    public function providerForTestTodayDeath(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                3,
                2
            ],
            [
                7,
                new \DateTime("2021-10-10"),
                3,
                0
            ],
            [
                7,
                new \DateTime("2021-01-02"),
                3,
                0
            ]
        ];
    }

    /**
     * @dataProvider providerForTestNearestBirth
     */
    public function testNearestBirth(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getNearestBirth()->count());
    }

    public function providerForTestNearestBirth(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                1,
                0
            ],
            [
                7,
                new \DateTime("1999-10-15"),
                3,
                1
            ],
            [
                7,
                new \DateTime("1999-12-27"),
                3,
                1
            ],
            [
                7,
                new \DateTime("1999-12-27"),
                10,
                2
            ]
        ];
    }

    /**
     * @dataProvider providerForTestNearestDeath
     */
    public function testNearestDeath(int $pastDay, \DateTime $today, int $nearestDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $pastDay,
            $today,
            $nearestDay
        );

        $this->assertEquals($expected, $repository->getNearestDeath()->count());
    }

    public function providerForTestNearestDeath(): array
    {
        return [
            [
                3,
                new \DateTime("2021-10-15"),
                10,
                2
            ],
            [
                7,
                new \DateTime("2021-10-15"),
                1,
                0
            ],
            [
                7,
                new \DateTime("1999-12-27"),
                3,
                1
            ],
            [
                7,
                new \DateTime("1999-12-27"),
                10,
                3
            ]
        ];
    }

    private function seedPeopleBirth(): void
    {
        (new GenderSeeder())->run();
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2010-10-09"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 1, "birth_date" => "2010-10-09",]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2010-10-13"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2010-10-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-12-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-10-17"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-10-22"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-10-15"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 1, "birth_date" => "2020-01-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "????-10-10"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-10-??"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => ""]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "2020-10-15"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "????-10-17"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "1979-12-28"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "1979-12-13"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "1988-01-04"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "1988-01-08"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "birth_date" => "????-01-01"]);
    }

    private function seedPeopleDeath(): void
    {
        (new GenderSeeder())->run();
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2010-10-09"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2010-10-09"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2010-10-13"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2010-10-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2020-12-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 1, "death_date" => "2020-10-17"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 1, "death_date" => "2020-10-22"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2020-10-15"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2020-01-01"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "????-10-10"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2020-10-??"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => ""]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => null]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "2020-10-15"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "????-10-17"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "1979-12-28"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "1979-12-13"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "1988-01-04"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "1988-01-08"]);
        PeopleEloquentModel::factory()->create(["is_unavailable" => 0, "death_date" => "????-01-01"]);
    }
}
