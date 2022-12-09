<?php

namespace Tests\Feature\Repositories\People\Ordering;

use App\Models\PersonShort as PersonModel;
use App\Repositories\People\Ordering\Name;
use Tests\TestCase;

class NameTest extends TestCase
{
    public function testCreate(): Name
    {
        $obj = new Name();
        $this->assertInstanceOf(Name::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     * @dataProvider sortProvider
     */
    public function testSort($arrayExpected, $arrayActual, Name $ordering)
    {
        $ordering->sort($arrayActual);
        $this->assertEquals($arrayExpected, $arrayActual);
    }

    public function sortProvider(): array
    {
        $obj1 = new PersonModel(1, "Иванов", collect(["Акикин"]), "Иван1", "Иванович", "2000-01-10");
        $obj2 = new PersonModel(1, "Петров", collect(["Акокин"]), null, "Петрович", null);
        $obj3 = new PersonModel(1, "Акакьев", collect([]), null, "Петрович", "2000-01-10");
        $obj4 = new PersonModel(1, "Иванов", collect([]), "Иван", "Иванович", null);

        return [
            [[$obj3, $obj4, $obj1, $obj2], [$obj1, $obj2, $obj3, $obj4]],
        ];
    }
}
