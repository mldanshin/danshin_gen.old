<?php

namespace Tests\Unit\View\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\View\Tree\Family;
use App\View\Tree\ParentChildrenRelation;
use App\View\Tree\Person;
use App\View\Tree\PointXY;
use Tests\DataProvider\View as ViewDataProvider;
use Tests\TestCase;

final class FamilyTest extends TestCase
{
    use ViewDataProvider;

    public function testCreate(): void
    {
        $data = $this->createProvider();

        $actual = new Family($data[0], $this->getStylePerson(), $data[4]);
        $this->assertInstanceOf(Family::class, $actual);
        $this->assertEquals($data[1], $actual->getPerson());
        $this->assertEquals($data[2], $actual->getMarriage());
        $this->assertEquals($data[3], $actual->getChildrens());

        $x = 100;
        $y = 100;
        $actual->setPoint($x, $y);
        $this->assertEquals(new PointXY($x, $y), $actual->getPoint());

        $actual->setPointWrapper($x, $y, new PointXY($x, $y));
        $this->assertInstanceOf(ParentChildrenRelation::class, $actual->getParentRelation());
    }

    public function createProvider(): array
    {
        $array = [
            0 => new PersonModel(
                1,
                "Petrov",
                null,
                "Ivan",
                "Ivanovich",
                "2000-01-01",
                null,
                true
            ),
            1 => new PersonModel(
                11,
                "Ivanov",
                null,
                "Artem",
                "Petrovich",
                "2010-01-01",
                "",
                false
            ),
            2 => new PersonModel(
                13,
                "Ivanov",
                null,
                "Artem",
                "Petrovich",
                "",
                "",
                false
            ),
            3 => new PersonModel(
                23,
                "Sidorov",
                null,
                "Yriy",
                "Maksimovich",
                "",
                "",
                false
            )
        ];

        $hasLinks = true;

        return [
            new FamilyModel(
                $array[0],
                collect([$array[1]]),
                collect([
                    new FamilyModel($array[2], collect(), collect()),
                    new FamilyModel($array[3], collect(), collect())
                ]),
            ),
            new Person($array[0], $this->getStylePerson(), $hasLinks),
            collect([new Person($array[1], $this->getStylePerson(), $hasLinks)]),
            collect([
                new Family(new FamilyModel($array[2], collect(), collect()), $this->getStylePerson(), $hasLinks),
                new Family(new FamilyModel($array[3], collect(), collect()), $this->getStylePerson(), $hasLinks)
            ]),
            $hasLinks
        ];
    }
}
