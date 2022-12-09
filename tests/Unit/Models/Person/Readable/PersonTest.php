<?php

namespace Tests\Unit\Models\Person\Readable;

use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Readable\Internet as InternetModel;
use App\Models\Person\Readable\Person as PersonModel;
use App\Models\Person\Readable\Photo as PhotoModel;
use App\Models\Person\Readable\Residence as ResidenceModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

final class PersonTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        bool $isUnavailable,
        int $genderId,
        string $surname,
        ?Collection $oldSurname,
        string $name,
        ?string $patronymic,
        string $birthDate,
        string $birthPlace,
        ?string $deathDate,
        ?string $burialPlace,
        ?string $note,
        ?Collection $activities,
        ?Collection $emails,
        ?Collection $internet,
        ?Collection $phones,
        ?Collection $residences,
        ?Collection $parents,
        ?Collection $marriage,
        ?Collection $children,
        ?Collection $brotherSister,
        ?Collection $photo,
        \DateTime $today
    ): void {
        $model = new PersonModel(
            $id,
            $isUnavailable,
            $genderId,
            $surname,
            $oldSurname,
            $name,
            $patronymic,
            $birthDate,
            $birthPlace,
            $deathDate,
            $burialPlace,
            $note,
            $activities,
            $emails,
            $internet,
            $phones,
            $residences,
            $parents,
            $marriage,
            $children,
            $brotherSister,
            $photo,
            $today
        );

        $this->assertInstanceOf(PersonModel::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($isUnavailable, $model->isUnavailable());
        if ($model->getDeathDate() === null) {
            $this->assertTrue($model->isLive());
        } else {
            $this->assertFalse($model->isLive());
        }
        $this->assertEquals($genderId, $model->getGenderId());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($oldSurname->all(), $model->getOldSurname()->all());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
        $this->assertEquals($birthDate, $model->getBirthDate());
        if ($model->getAge()) {
            $this->assertInstanceOf(\DateInterval::class, $model->getAge());
        } else {
            $this->assertNull($model->getAge());
        }
        $this->assertEquals($birthPlace, $model->getBirthPlace());
        $this->assertEquals($deathDate, $model->getDeathDate());
        if ($model->getDeathDateInterval()) {
            $this->assertInstanceOf(\DateInterval::class, $model->getDeathDateInterval());
        } else {
            $this->assertNull($model->getDeathDateInterval());
        }
        $this->assertEquals($burialPlace, $model->getBurialPlace());
        $this->assertEquals($note, $model->getNote());
        $this->assertEquals($activities->all(), $model->getActivities()->all());
        $this->assertEquals($emails->all(), $model->getEmails()->all());
        $this->assertEquals($internet->all(), $model->getInternet()->all());
        $this->assertEquals($phones->all(), $model->getPhones()->all());
        $this->assertEquals($residences->all(), $model->getResidences()->all());
        $this->assertEquals($parents->all(), $model->getParents()->all());
        $this->assertEquals($marriage->all(), $model->getMarriages()->all());
        $this->assertEquals($children->all(), $model->getChildren()->all());
        $this->assertEquals($brotherSister->all(), $model->getBrothersSisters()->all());
        $this->assertEquals($photo->all(), $model->getPhoto()->all());
    }

    public function createProvider()
    {
        return [
            [
                1,
                true,
                2,
                "Ivanov",
                collect(["Sidorov", "Petrov"]),
                "Ivan",
                "Ivanovich",
                "1990-??-??",
                "",
                null,
                null,
                null,
                collect(["work"]),
                collect(["mail@danshin.net"]),
                collect([new InternetModel(1, "", "")]),
                collect(["904-570-00-00"]),
                collect([new ResidenceModel(1, "", "")]),
                collect([
                    new PersonShortModel(
                        1,
                        "Ivanov",
                        collect(["Sidorov", "Дадашников"]),
                        "Ivan",
                        "Ivanovich",
                        "2000-01-10"
                    ),
                    new PersonShortModel(
                        1,
                        "Petrov",
                        collect(["Koslov", "Ivanov"]),
                        "Dev",
                        "Petrovich",
                        "2000-01-10"
                    ),
                ]),
                collect([new PersonShortModel(1, "Daw", collect(["Low", "Pot"]), "Maksim", "Er", "2000-01-10")]),
                collect([new PersonShortModel(1, "Qur", collect(["Dfre", "Wert"]), "Vbsq", "Ertf", "1995-12-10")]),
                collect([new PersonShortModel(1, "Wer", collect(["Vbgt", "Dfr"]), "Derv", "Sidorovbg", "1995-12-10")]),
                collect([new PhotoModel("danshin.net", "/home/danshin", null), new PhotoModel("test-go.ru", "/home/test-go", "2000-01-01")]),
                new \DateTime()
            ],
            [
                0,
                false,
                1,
                "Wweew",
                collect(["Ffdw"]),
                "Ddwe",
                "Dsdwsdw",
                "2000-01-30",
                "Kemerovo",
                null,
                null,
                null,
                collect([]),
                collect([]),
                collect([new InternetModel(2, "", "")]),
                collect([]),
                collect([new ResidenceModel(1, "", "")]),
                collect([
                    new PersonShortModel(1, "Dsdsd", collect(["Sqdsw", "Lodmdid"]), "Dswd", "Dwdw", "2400-01-10"),
                    new PersonShortModel(1, "Dsdwd", collect(["Dwsdw", "DWdwdw"]), "DWwwqqqw", "DWdd", "2010-01-10"),
                ]),
                collect([new PersonShortModel(1, "Ddwwdwd", collect(["Wp", "Vd"]), "As", "Df", "1995-12-10")]),
                collect([new PersonShortModel(1, "Fg", collect(["Hj", "Kl"]), "Sas", "Qwe", "2030-01-10")]),
                collect([new PersonShortModel(1, "Rty", collect(["Yui", "Opi"]), "Asd", "Fgh", "2020-01-10")]),
                collect([new PhotoModel("aaaaa.net", "/home/aaaaa", "2020-03-03"), new PhotoModel("blabla.ru", "/home/blabla", null)]),
                new \DateTime()
            ]
        ];
    }
}
