<?php

namespace Tests\Unit\Models\Person\Editable\Request;

use App\Models\Person\Editable\Internet as InternetModel;
use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\Residence as ResidenceModel;
use App\Models\Person\Editable\Request\Marriage as MarriageModel;
use App\Models\Person\Editable\Request\ParentModel as ParentModel;
use App\Models\Person\Editable\Request\Person as PersonModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

final class PersonTest extends TestCase
{
    /**
     * @dataProvider createProvider
     * @param Collection|OldSurnameModel[] $oldSurname
     * @param Collection|string[] $activities
     * @param Collection|string[] $emails
     * @param Collection|InternetModel[] $internet
     * @param Collection|string[] $phones
     * @param Collection|ResidenceModel[] $residences
     * @param Collection|ParentModel[] $parents
     * @param Collection|MarriageModel[] $marriages
     * @param Collection|PhotoModel[] $photo
     */
    public function testCreate(
        int $id,
        bool $isUnavailable,
        bool $isLive,
        int $gender,
        ?string $surname,
        Collection $oldSurname,
        ?string $name,
        ?string $patronymic,
        ?string $birthDate,
        ?string $birthPlace,
        ?string $deathDate,
        ?string $burialPlace,
        ?string $note,
        Collection $activities,
        Collection $emails,
        Collection $internet,
        Collection $phones,
        Collection $residences,
        Collection $parents,
        Collection $marriages,
        Collection $photo
    ): void {
        $model = new PersonModel(
            $id,
            $isUnavailable,
            $isLive,
            $gender,
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
            $marriages,
            $photo
        );

        $this->assertInstanceOf(PersonModel::class, $model);
        $this->assertEquals($id, $model->getId());
        $this->assertEquals($isUnavailable, $model->isUnavailable());
        $this->assertEquals($gender, $model->getGender());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($oldSurname->all(), $model->getOldSurname()->all());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
        $this->assertEquals($birthDate, $model->getBirthDate());
        $this->assertEquals($birthPlace, $model->getBirthPlace());
        if ($isLive === true) {
            $this->assertNull($model->getDeathDate());
        } elseif ($isLive === false && $deathDate === null) {
            $this->assertEquals("", $model->getDeathDate());
        }
        $this->assertEquals($burialPlace, $model->getBurialPlace());
        $this->assertEquals($note, $model->getNote());
        $this->assertEquals($activities->all(), $model->getActivities()->all());
        $this->assertEquals($emails->all(), $model->getEmails()->all());
        $this->assertEquals($internet->all(), $model->getInternet()->all());
        $this->assertEquals($phones->all(), $model->getPhones()->all());
        $this->assertEquals($residences->all(), $model->getResidences()->all());
        $this->assertEquals($parents->all(), $model->getParents()->all());
        $this->assertEquals($marriages->all(), $model->getMarriages()->all());
        $this->assertEquals($photo->all(), $model->getPhoto()->all());
    }

    public function createProvider()
    {
        return [
            [
                1,
                true,
                false,
                1,
                "Ivanov",
                collect([
                    new OldSurnameModel("Sidorov", 3),
                    new OldSurnameModel("Petrov", 3)
                    ]),
                "Ivan",
                "Ivanovich",
                "1990-??-??",
                "",
                "",
                "",
                "",
                collect(["work"]),
                collect(["mail@danshin.net"]),
                collect([new InternetModel("", "")]),
                collect(["904-570-00-23"]),
                collect([new ResidenceModel("", "")]),
                collect([new ParentModel(10, 2)]),
                collect([new MarriageModel(2, 10, 2)]),
                collect([
                    new PhotoModel("https://test-go.ru/image.png", "img/image.png", "2000-01-09", 1),
                    new PhotoModel("https://danshin.net/image.png", "storahe/image.png", "2001-01-09", 3)
                ])
            ],
            [
                0,
                false,
                false,
                2,
                "Petrov",
                collect([
                    new OldSurnameModel("Sidorov", 3)
                    ]),
                "Den",
                "Maksimovich",
                "2000-01-30",
                "",
                "2020-10-11",
                "",
                "",
                collect([]),
                collect([]),
                collect([new InternetModel("", "")]),
                collect([]),
                collect([new ResidenceModel("", "")]),
                collect([new ParentModel(10, 2), new ParentModel(18, 32)]),
                collect([new MarriageModel(4, 10, 2)]),
                collect([
                    new PhotoModel("https://blabla.com/image.jpg", "image.jpg", "2012-01-09", 34),
                ])
            ],
            [
                3,
                false,
                true,
                2,
                "Petrov",
                collect([
                    new OldSurnameModel("Sidorov", 3)
                    ]),
                "Den",
                "Maksimovich",
                "2000-01-30",
                "",
                "",
                "",
                "",
                collect([]),
                collect([]),
                collect([new InternetModel("", "")]),
                collect([]),
                collect([new ResidenceModel("", "")]),
                collect([new ParentModel(10, 2), new ParentModel(18, 32)]),
                collect([new MarriageModel(4, 10, 2)]),
                collect([
                    new PhotoModel("https://blabla.com/image.jpg", "image.jpg", "2012-01-09", 34),
                ])
            ],
            [
                3,
                false,
                true,
                2,
                "Petrov",
                collect([
                    new OldSurnameModel("Sidorov", 3)
                    ]),
                "Andrey",
                "Maksimovich",
                "2000-01-30",
                "",
                "2020-01-01",
                "",
                "",
                collect([]),
                collect([]),
                collect([new InternetModel("", "")]),
                collect([]),
                collect([new ResidenceModel("", "")]),
                collect([new ParentModel(10, 2), new ParentModel(18, 32)]),
                collect([new MarriageModel(4, 10, 2)]),
                collect([
                    new PhotoModel("https://blabla.com/image.jpg", "image.jpg", "2012-01-09", 34),
                ])
            ]
        ];
    }
}
