<?php

namespace Tests\Feature\Repositories\People\Ordering;

use App\Models\PersonShort as PersonModel;
use App\Repositories\People\Ordering\Age;
use Tests\TestCase;

class AgeTest extends TestCase
{
    public function testCreate(): Age
    {
        $obj = new Age();
        $this->assertInstanceOf(Age::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     * @dataProvider sortProvider
     */
    public function testSort($arrayExpected, $arrayActual, Age $ordering)
    {
        $ordering->sort($arrayActual);
        $this->assertEquals($arrayExpected, $arrayActual);
    }

    public function sortProvider(): array
    {
        $obj1 = new PersonModel(1, "Иванов", collect(["Акикин"]), "Иван1", "Иванович", "1990-01-10");
        $obj2 = new PersonModel(1, "Петров", collect(["Акокин"]), null, "Петрович", "2011-01-12");
        $obj3 = new PersonModel(1, "Акакьев", collect([]), null, "Петрович", "2011-01-10");
        $obj4 = new PersonModel(1, "Иванов", collect([]), "Иван", "Иванович", null);

        return [
            [[$obj4, $obj1, $obj3, $obj2], [$obj1, $obj2, $obj3, $obj4]],
        ];
    }
}
