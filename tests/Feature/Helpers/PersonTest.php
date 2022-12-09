<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Person as PersonHelper;
use App\Models\Eloquent\Gender;
use App\Models\Eloquent\MarriageRole;
use App\Models\Eloquent\ParentRole;
use Database\Seeders\Testing\GenderSeeder;
use Database\Seeders\Testing\MarriageRoleSeeder;
use Database\Seeders\Testing\ParentRoleSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class PersonTest extends TestCase
{
    use DatabaseMigrations;

    public function testGender(): void
    {
        $this->seed(GenderSeeder::class);

        $collection = Gender::all();
        foreach ($collection as $item) {
            $this->assertEquals(__("db.gender.{$item->id}"), PersonHelper::gender($item->id));
        }
    }

    /**
     * @dataProvider surnameStringProvider
     */
    public function testSurnameString($value): void
    {
        $this->assertIsString(PersonHelper::surname($value));
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    public function surnameStringProvider(): array
    {
        return [
            ["Ivanov"],
            ["Petrov"],
            ["Sidorov"]
        ];
    }

    public function testSurnameEmpty(): void
    {
        $this->assertEquals(__("person.surname.null"), PersonHelper::surname(""));
    }

    /**
     * @dataProvider oldSurnameStringProvider
     */
    public function testOldSurnameString($expected, $actual): void
    {
        $this->assertEquals($expected, PersonHelper::oldSurname($actual));
    }

    public function oldSurnameStringProvider(): array
    {
        return [
            ["(Ivanov)", collect(["Ivanov"])],
            ["(Ivanov,Petrov)", collect(["Ivanov", "Petrov"])],
            ["", collect([])],
            ["", null],
        ];
    }

    /**
     * @dataProvider nameStringProvider
     */
    public function testNameString($value): void
    {
        $this->assertIsString(PersonHelper::surname($value));
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    public function nameStringProvider(): array
    {
        return [
            ["Den"],
            ["Maksim"],
            ["Ivan"],
        ];
    }

    public function testNameEmpty(): void
    {
        $this->assertEquals(__("person.name.null"), PersonHelper::name(""));
    }

    /**
     * @dataProvider patronymicStringProvider
     */
    public function testPatronymicString($value): void
    {
        $this->assertIsString(PersonHelper::surname($value));
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    public function patronymicStringProvider(): array
    {
        return [
            ["Maksimovich"],
            ["Ivanovich"],
            ["Petrovich"]
        ];
    }

    public function testPatronymicEmpty(): void
    {
        $this->assertEquals(__("person.patronymic.null"), PersonHelper::patronymic(""));
    }

    public function testPatronymicNull(): void
    {
        $this->assertEquals("", PersonHelper::patronymic(null));
    }

    /**
     * @dataProvider patronymicEditProvider
     */
    public function testPatronymicEdit(?string $expectedParam, string $expectedReturn): void
    {
        $this->assertEquals($expectedReturn, PersonHelper::patronymicEdit($expectedParam));
    }

    public function patronymicEditProvider(): array
    {
        return [
            [null, "!"],
            ["", ""],
            ["Petrovich", "Petrovich"],
        ];
    }

    public function testMarriage(): void
    {
        $this->seed(MarriageRoleSeeder::class);

        $collection = MarriageRole::all();
        foreach ($collection as $item) {
            $this->assertEquals(__("db.marriage_role.{$item->id}"), PersonHelper::marriage($item->id));
        }
    }

    public function testParent(): void
    {
        $this->seed(ParentRoleSeeder::class);

        $collection = ParentRole::all();
        foreach ($collection as $item) {
            $this->assertEquals(__("db.parent_role.{$item->id}"), PersonHelper::parent($item->id));
        }
    }
}
