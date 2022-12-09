<?php

namespace Tests\Feature\Repositories\Person\Editable\Request;

use App\Models\Eloquent\Activity as ActivityEloquentModel;
use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\Email as EmailEloquentModel;
use App\Models\Eloquent\Internet as InternetEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\OldSurname as OldSurnameEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\Phone as PhoneEloquentModel;
use App\Models\Eloquent\Photo as PhotoEloquentModel;
use App\Models\Eloquent\Residence as ResidenceEloquentModel;
use App\Models\Person\Editable\Internet as InternetModel;
use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\Residence as ResidenceModel;
use App\Models\Person\Editable\Request\Marriage as MarriageModel;
use App\Models\Person\Editable\Request\ParentModel as ParentModel;
use App\Models\Person\Editable\Request\Person as PersonModel;
use App\Repositories\Person\Editable\Request\Person as PersonRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Tests\DataProvider\Dates;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\TestCase;

final class PersonSuccessTest extends TestCase
{
    use Dates;
    use PhotoDataProvider;
    use RefreshDatabase;
    use WithFaker;

    public function testCreate(): PersonRepository
    {
        $repository = new PersonRepository();
        $this->assertInstanceOf(PersonRepository::class, $repository);
        return $repository;
    }

    public function testStore(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $repository = new PersonRepository();

        $people = PeopleEloquentModel::get();
        $genders = GenderEloquentModel::get();
        for ($i = 0; $i < 10; $i++) {
            $fileTemp = $this->createTempPhotoFile();
            $photo = collect([
                new PhotoModel(
                    $fileTemp,
                    $fileTemp,
                    $this->faker->randomElement($this->getDateBetween()),
                    $this->faker->unique()->numberBetween(1, 1000)
                )
            ]);
            $expected = $this->getPerson(0, $people, $genders->random(), $photo);
            $actual = PeopleEloquentModel::find($repository->store($expected));
            $this->comparePerson($expected, $actual);
        }
    }

    public function testUpdate(): void
    {
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        $repository = new PersonRepository();

        $people = PeopleEloquentModel::doesntHave("photo")->limit(10)->get();

        foreach ($people as $person) {
            $fileTemp = $this->createTempPhotoFile();
            $photo = collect([
                new PhotoModel(
                    $fileTemp,
                    $fileTemp,
                    $this->faker->randomElement($this->getDateBetween()),
                    $this->faker->unique()->numberBetween(1, 1000)
                )
            ]);

            $expected = $this->getPerson($person->id, $people, $person->gender()->first(), $photo);
            $actual = PeopleEloquentModel::find($repository->update($expected));
            $this->comparePerson($expected, $actual);
        }
    }

    public function testDelete(): void
    {
        $this->seed();
        $this->setConfigFakeDisk();

        $repository = new PersonRepository();

        for ($i = 0; $i < 10; $i++) {
            $id = PeopleEloquentModel::value("id");
            $repository->delete($id);

            $this->assertNull(PeopleEloquentModel::where("id", $id)->first());
            $this->assertNull(ActivityEloquentModel::where("person_id", $id)->first());
            $this->assertNull(EmailEloquentModel::where("person_id", $id)->first());
            $this->assertNull(InternetEloquentModel::where("person_id", $id)->first());
            $this->assertNull(OldSurnameEloquentModel::where("person_id", $id)->first());
            $this->assertNull(PhoneEloquentModel::where("person_id", $id)->first());
            $this->assertNull(ResidenceEloquentModel::where("person_id", $id)->first());
            $this->assertNull(ParentChildEloquentModel::where("parent_id", $id)->orWhere("child_id", $id)->first());
            $this->assertNull(MarriageEloquentModel::where("person1_id", $id)->orWhere("person2_id", $id)->first());
            $this->assertNull(PhotoEloquentModel::where("person_id", $id)->first());
        }
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @param Collection|PhotoModel[] $photo
     */
    private function getPerson(
        int $id,
        Collection $people, 
        GenderEloquentModel $gender,
        Collection $photo
    ): PersonModel {
        return new PersonModel(
            $id,
            $this->faker->randomElement([0, 1]),
            $this->faker->randomElement([0, 1]),
            $gender->id,
            $this->faker->lastName(),
            collect([
                new OldSurnameModel(
                    $this->faker->unique()->lastName(),
                    $this->faker->unique()->numberBetween(1, 1000)
                ),
                new OldSurnameModel(
                    $this->faker->unique()->lastName(),
                    $this->faker->unique()->numberBetween(1, 1000)
                ),
            ]),
            $this->faker->firstName(),
            $this->faker->firstName(),
            $this->faker->randomElement($this->getBirthDate()),
            $this->faker->city(),
            $this->faker->randomElement($this->getDeathDate()),
            $this->faker->city(),
            $this->faker->text(),
            collect([
                $this->faker->unique()->text(),
                $this->faker->unique()->text()
            ]),
            collect([
                $this->faker->unique()->email(),
                $this->faker->unique()->email()
            ]),
            collect([
                new InternetModel(
                    $this->faker->unique()->url(),
                    $this->faker->text()
                ),
                new InternetModel(
                    $this->faker->unique()->url(),
                    $this->faker->text()
                ),
            ]),
            collect([
                $this->faker->e164PhoneNumber(),
                $this->faker->e164PhoneNumber()
            ]),
            collect([
                new ResidenceModel(
                    $this->faker->address(),
                    $this->faker->randomElement($this->getDateBetween()),
                ),
                new ResidenceModel(
                    $this->faker->address(),
                    $this->faker->randomElement($this->getDateBetween()),
                ),
            ]),
            $parents = $this->getParents($id, $people, $gender),
            $this->getMarriages($id, $people, $gender, $parents->map(
                fn($item) => $item->getPerson()
            )),
            $photo,
        );
    }

    private function comparePerson(PersonModel $expected, PeopleEloquentModel $actual)
    {
        $this->assertEquals($expected->isUnavailable(), (bool)$actual->is_unavailable);
        $this->assertEquals($expected->getGender(), $actual->gender_id);
        $this->assertEquals($expected->getSurname(), $actual->surname);
        foreach ($expected->getOldSurname() as $item) {
            $this->assertContains(
                $item->getSurname(),
                $actual->oldSurname()->pluck("surname")->all()
            );
            $this->assertTrue(in_array(
                $item->getOrder(),
                $actual->oldSurname()->pluck("_order")->all()
            ));
        }
        $this->assertEquals($expected->getName(), $actual->name);
        $this->assertEquals($expected->getPatronymic(), $actual->patronymic);
        $this->assertEquals($expected->getBirthDate(), $actual->birth_date);
        $this->assertEquals($expected->getBirthPlace(), $actual->birth_place);
        $this->assertEquals($expected->getDeathDate(), $actual->death_date);
        $this->assertEquals($expected->getBurialPlace(), $actual->burial_place);
        $this->assertEquals($expected->getNote(), $actual->note);
        foreach ($expected->getActivities() as $item) {
            $this->assertContains($item, $actual->activities()->pluck("name")->all());
        }
        foreach ($expected->getEmails() as $item) {
            $this->assertContains($item, $actual->emails()->pluck("name")->all());
        }
        foreach ($expected->getInternet() as $item) {
            $this->assertContains(
                $item->getName(),
                $actual->internet()->pluck("name")->all()
            );
            $this->assertContains(
                $item->getUrl(),
                $actual->internet()->pluck("url")->all()
            );
        }
        foreach ($expected->getPhones() as $item) {
            $this->assertContains($item, $actual->phones()->pluck("name")->all());
        }
        foreach ($expected->getResidences() as $item) {
            $this->assertContains(
                $item->getName(),
                $actual->residences()->pluck("name")->all()
            );
            $this->assertContains(
                $item->getDate(),
                $actual->residences()->pluck("date_info")->all()
            );
        }
        foreach ($expected->getParents() as $item) {
            $this->assertTrue(in_array(
                $item->getPerson(),
                $actual->parents()->pluck("parent_id")->all()
            ));
            $this->assertTrue(in_array(
                $item->getRole(),
                $actual->parents()->pluck("parent_role_id")->all()
            ));
            $this->assertTrue(in_array(
                $actual->id,
                $actual->parents()->pluck("child_id")->all()
            ));
        }
        foreach ($expected->getMarriages() as $item) {
            $this->assertTrue(in_array(
                $actual->id,
                MarriageEloquentModel::pluck("person1_id")->all()
            ));
            $this->assertTrue(in_array(
                $item->getSoulmate(),
                MarriageEloquentModel::pluck("person2_id")->all()
            ));

            $roleScopeIdModel = MarriageEloquentModel::select("role_scope_id")
                ->where([
                    "person1_id" => $actual->id,
                    "person2_id" => $item->getSoulmate(),
                ])
                ->value("id");
            $roleScopeIdPerson = MarriageRoleScopeEloquentModel::where([
                "role1_id" => $actual->id,
                "role2_id" => $item->getSoulmate(),
            ])->value("id");
            $this->assertEquals($roleScopeIdModel, $roleScopeIdPerson);
        }
        foreach ($expected->getPhoto() as $item) {
            $this->assertTrue(in_array(
                File::basename($item->getPathRelative()),
                $actual->photo()->pluck("file")->all()
            ));
            $this->assertContains(
                $item->getDate(),
                $actual->photo()->pluck("_date")->all()
            );
            $this->assertTrue(in_array(
                $item->getOrder(),
                $actual->photo()->pluck("_order")->all()
            ));
        }
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @return Collection|ParentModel[]
     */
    private function getParents(int $id, Collection $people, GenderEloquentModel $gender): Collection
    {
        $people = $people->except($id);
        if ($people->count() > 0) {
            $parent = $people->random();
            return collect([
                new ParentModel(
                    $parent->id,
                    $parent->gender()->first()->parents()->pluck("parent_id")->random(),
                )
            ]);
        } else {
            return collect();
        }
    }

    /**
     * @param Collection|PeopleEloquentModel[] $people
     * @param Collection|int[] $parents
     * @return Collection|MarriageModel[]
     */
    private function getMarriages(
        int $id,
        Collection $people,
        GenderEloquentModel $gender,
        Collection $parents
    ): Collection {
        if ($people->count() > 0) {
            $currentRole = $gender->marriages()->get()->random();

            $soulmateRole = $currentRole->scope1()->get()->random();

            $soulmateCandidates = $soulmateRole->role2()->first()
                ->genders()->get()->random()
                ->people()->get();

            if ($soulmateCandidates->count() > 0) {
                $soulmate = $soulmateCandidates->except(array_merge($parents->all(), [$id]));
                if ($soulmate->count() > 0) {
                    return collect([
                        new MarriageModel(
                            $currentRole->id,
                            $soulmate->random()->id,
                            $soulmateRole->role2_id
                        )
                    ]);
                } else {
                    return collect();
                }
            } else {
                return collect();
            }
        } else {
            return collect();
        }
    }
}
