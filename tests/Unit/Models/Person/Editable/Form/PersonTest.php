<?php

namespace Tests\Unit\Models\Person\Editable\Form;

use App\Models\Pair as PairModel;
use App\Models\PersonShort as PersonShortModel;
use App\Models\Person\Editable\Internet as InternetModel;
use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\Residence as ResidenceModel;
use App\Models\Person\Editable\Form\Gender as GenderModel;
use App\Models\Person\Editable\Form\Marriages as MarriagesModel;
use App\Models\Person\Editable\Form\Parents as ParentsModel;
use App\Models\Person\Editable\Form\ParentModel as ParentModel;
use App\Models\Person\Editable\Form\Person as PersonModel;
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
     * @param Collection|PhotoModel[] $photo
     */
    public function testCreate(
        int $id,
        bool $isUnavailable,
        bool $isLiveProperty,
        GenderModel $gender,
        string $surname,
        Collection $oldSurname,
        string $name,
        ?string $patronymic,
        string $birthDate,
        string $birthPlace,
        ?string $deathDate,
        string $burialPlace,
        string $note,
        Collection $activities,
        Collection $emails,
        Collection $internet,
        Collection $phones,
        Collection $residences,
        ParentsModel $parents,
        MarriagesModel $marriages,
        Collection $photo,
    ): void {
        $model = new PersonModel(
            $id,
            $isUnavailable,
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
        $this->assertEquals($isLiveProperty, $model->isLive());
        $this->assertEquals($gender, $model->getGender());
        $this->assertEquals($surname, $model->getSurname());
        $this->assertEquals($oldSurname->all(), $model->getOldSurname()->all());
        $this->assertEquals($name, $model->getName());
        $this->assertEquals($patronymic, $model->getPatronymic());
        $this->assertEquals($birthDate, $model->getBirthDate());
        $this->assertEquals($birthPlace, $model->getBirthPlace());
        $this->assertEquals($deathDate, $model->getDeathDate());
        $this->assertEquals($burialPlace, $model->getBurialPlace());
        $this->assertEquals($note, $model->getNote());
        $this->assertEquals($activities->all(), $model->getActivities()->all());
        $this->assertEquals($emails->all(), $model->getEmails()->all());
        $this->assertEquals($internet->all(), $model->getInternet()->all());
        $this->assertEquals($phones->all(), $model->getPhones()->all());
        $this->assertEquals($residences->all(), $model->getResidences()->all());
        $this->assertEquals($parents, $model->getParents());
        $this->assertEquals($marriages, $model->getMarriages());
        $this->assertEquals($photo->all(), $model->getPhoto()->all());
    }

    public function createProvider()
    {
        return [
            [
                1,
                true,
                false,
                new GenderModel(
                    collect([new PairModel(1, "man"), new PairModel(2, "woman")]),
                    1
                ),
                "Ivanov",
                collect([
                    new OldSurnameModel("Sidorov", 3),
                    new OldSurnameModel("Petrov", 3)
                    ]),
                "Ivan",
                null,
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
                new ParentsModel(
                    collect([new PairModel(1, "mother"), new PairModel(2, "father")]),
                    collect([
                        new ParentModel(
                            10,
                            collect([
                                new PersonShortModel(
                                    10,
                                    "Ivanov",
                                    collect(["Sidorov", "Petrov"]),
                                    "Ivan",
                                    "Ivanovich",
                                    "2000-01-10"
                                )
                            ]),
                            2,
                            collect([new PairModel(1, "mother"), new PairModel(2, "father")]),
                        )
                    ])
                ),
                new MarriagesModel(
                    collect([new PairModel(1, "boyfriend"), new PairModel(2, "girlfriend")]),
                    collect([
                        new ParentModel(
                            10,
                            collect([
                                new PersonShortModel(10, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10")
                            ]),
                            2,
                            collect([new PairModel(1, "boyfriend"), new PairModel(2, "girlfriend")]),
                        )
                    ])
                ),
                collect([
                    new PhotoModel("https://test-go.ru/image.png", "img/image.png", "2000-01-09", 1),
                    new PhotoModel("https://danshin.net/image.png", "storahe/image.png", "2001-01-09", 3)
                ])
            ],
            [
                0,
                false,
                true,
                new GenderModel(
                    collect([new PairModel(1, "man"), new PairModel(2, "woman")]),
                    2
                ),
                "Petrov",
                collect([
                    new OldSurnameModel("Sidorov", 3)
                    ]),
                "Den",
                "Maksimovich",
                "2000-01-30",
                "",
                null,
                "",
                "",
                collect([]),
                collect([]),
                collect([new InternetModel("", "")]),
                collect([]),
                collect([new ResidenceModel("", "")]),
                new ParentsModel(
                    collect([new PairModel(2, "father"), new PairModel(1, "mother")]),
                    collect([
                        new ParentModel(
                            10,
                            collect([new PersonShortModel(10, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10")]),
                            2,
                            collect([new PairModel(1, "mother"), new PairModel(2, "father")]),
                        )
                    ])
                ),
                new MarriagesModel(
                    collect([new PairModel(1, "boyfriend"), new PairModel(2, "girlfriend")]),
                    collect([
                        new ParentModel(
                            10,
                            collect([new PersonShortModel(10, "Ivanov", collect(["Sidorov", "Petrov"]), "Ivan", "Ivanovich", "2000-01-10")]),
                            2,
                            collect([new PairModel(1, "boyfriend"), new PairModel(2, "girlfriend")]),
                        )
                    ])
                ),
                collect([
                    new PhotoModel("https://blabla.com/image.jpg", "image.jpg", "2012-01-09", 34),
                ])
            ]
        ];
    }
}
