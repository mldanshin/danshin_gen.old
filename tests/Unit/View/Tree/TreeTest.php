<?php

namespace Tests\Unit\View\Tree;

use App\Models\Tree\Family as FamilyModel;
use App\Models\Tree\Person as PersonModel;
use App\Models\Tree\PersonShort as PersonShortModel;
use App\Models\Tree\Tree as TreeModel;
use App\View\Tree\Family;
use App\View\Tree\Size;
use App\View\Tree\Tree;
use Tests\DataProvider\View as ViewDataProvider;
use Tests\TestCase;

final class TreeTest extends TestCase
{
    use ViewDataProvider;

    public function testCreate(): void
    {
        $data = $this->createProvider();

        $actual = new Tree($data[0], $data[3], $data[4], $data[5]);
        $this->assertInstanceOf(Tree::class, $actual);
        $this->assertEquals($data[1], $actual->getPersonTarget());
        $this->assertEquals($data[2], $actual->getFamily());
        $this->assertInstanceOf(Size::class, $actual->getSize());
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
            ),
            4 => new PersonShortModel(30, "Danshin", "Maksim", "Leonidovich")
        ];

        $hasLinks = false;

        $familyModel = new FamilyModel(
            $array[0],
            collect([$array[1]]),
            collect([
                new FamilyModel($array[2], collect(), collect()),
                new FamilyModel($array[3], collect(), collect())
            ]),
        );
        $family = new Family($familyModel, $this->getStylePerson(), $hasLinks);
        $family->setPoint(0, 0);

        return [
            new TreeModel(
                $array[4],
                $familyModel
            ),
            $array[4],
            $family,
            1280,
            720,
            $hasLinks
        ];
    }
}
