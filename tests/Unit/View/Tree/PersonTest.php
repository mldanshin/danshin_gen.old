<?php

namespace Tests\Unit\View\Tree;

use App\Models\Tree\Person as PersonModel;
use App\View\Tree\Link;
use App\View\Tree\Person;
use App\View\Tree\PointXY;
use App\View\Tree\Size;
use App\View\Tree\Text;
use Tests\DataProvider\View as ViewDataProvider;
use Tests\TestCase;

final class PersonTest extends TestCase
{
    use ViewDataProvider;

    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        PersonModel $model,
        bool $hasLinks,
        Text $expectedPeriodLive,
        array $points
    ): void {
        $stylePerson = $this->getStylePerson();

        $actual = new Person($model, $stylePerson, $hasLinks);

        $expectedCard = $this->getExpectedCardLink($model->getId(), $stylePerson->getButton(), $hasLinks);
        $expectedTree = $this->getExpectedTreeLink($model->getId(), $stylePerson->getButton(), $hasLinks);

        $this->assertInstanceOf(Person::class, $actual);
        $this->assertEquals((string)$model->getId(), $actual->getId());
        $this->assertEquals(new Text($model->getSurname()), $actual->getSurname());
        if ($model->getOldSurname() === null) {
            $this->assertNull($actual->getOldSurname());
        } else {
            $this->assertEquals(new Text($model->getOldSurname()), $actual->getOldSurname());
        }
        $this->assertEquals(new Text($model->getName()), $actual->getName());
        $this->assertEquals(new Text($model->getPatronymic()), $actual->getPatronymic());
        $this->assertEquals($expectedPeriodLive, $actual->getPeriodLive());
        $this->assertEquals($model->isPersonTarget(), $actual->isPersonTarget());
        $this->assertEquals($expectedCard, $actual->getLinkCard());
        $this->assertEquals($expectedTree, $actual->getLinkTree());

        $actual->setPoint($points["start_x"], $points["start_y"]);
        $this->assertEquals(new PointXY($points["start_x"], $points["start_y"]), $actual->getPoint());
    }

    /**
     * @return array[]
     */
    public function createProvider(): array
    {
        return [
            [
                new PersonModel(
                    1,
                    "Petrov",
                    null,
                    "Ivan",
                    "Ivanovich",
                    "2000-01-01",
                    null,
                    true
                ),
                true,
                new Text("(01.01.2000)"),
                [
                    "start_x" => 0,
                    "start_y" => 0,
                ]
            ],
            [
                new PersonModel(
                    11,
                    "Ivanov",
                    null,
                    "Artem",
                    "Petrovich",
                    "2010-01-01",
                    "",
                    false
                ),
                false,
                new Text("(01.01.2010-?)"),
                [
                    "start_x" => 10,
                    "start_y" => 10,
                ]
            ],
            [
                new PersonModel(
                    13,
                    "Ivanov",
                    null,
                    "Artem",
                    "Petrovich",
                    "",
                    "",
                    false
                ),
                true,
                new Text("(?-?)"),
                [
                    "start_x" => 50,
                    "start_y" => 100
                ]
            ],
        ];
    }

    private function getExpectedCardLink(int $idPerson, Size $buttonSize, bool $hasLinks): ?Link
    {
        if ($hasLinks) {
            return new Link(
                $idPerson,
                route("person.show", $idPerson),
                route("partials.person.show", $idPerson),
                asset("img/person/card.svg"),
                $buttonSize
            );
        } else {
            return null;
        }
    }

    private function getExpectedTreeLink(int $idPerson, Size $buttonSize, bool $hasLinks): ?Link
    {
        if ($hasLinks) {
            return new Link(
                $idPerson,
                route("tree", $idPerson),
                route("partials.tree.index"),
                asset("img/tree/tree.svg"),
                $buttonSize
            );
        } else {
            return null;
        }
    }
}
