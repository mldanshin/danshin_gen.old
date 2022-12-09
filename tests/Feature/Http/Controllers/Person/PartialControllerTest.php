<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\MarriageRole as MarriageRoleEloquentModel;
use App\Models\Eloquent\ParentRole as ParentRoleEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class PartialControllerTest extends TestCase
{
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    /**
     * @dataProvider getListInputSuccessProvider
     */
    public function testGetListInputSuccess($nameView)
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("partials.person.list_input", "partials.person.partials.editor.$nameView"));
        $response->assertStatus(200);
    }

    public function getListInputSuccessProvider()
    {
        return [
            ["activity"],
            ["email"],
            ["internet"],
            ["old-surname"],
            ["phone"],
            ["residence"],
        ];
    }

    /**
     * @dataProvider getListInputWrongProvider
     */
    public function testGetListInputWrong($nameView)
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->get(route("partials.person.list_input", "partials.person.partials.editor.$nameView"));
        $response->assertStatus(500);
    }

    public function getListInputWrongProvider()
    {
        return [
            ["blabla"],
            ["one"],
            [4],
            ["www"],
            ["parent"],
            ["marriage"],
        ];
    }

    public function testGetParentSuccess()
    {
        $this->seed();

        $personId = PeopleEloquentModel::pluck("id");
        $roleParent = ParentRoleEloquentModel::pluck("id");

        for ($i = 0; $i < 5; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.parent"), [
                    "person_id" => $personId->random(),
                    "parent_role" => $roleParent->random()
                ]);
            $response->assertStatus(200);
        }
    }

    /**
     * @dataProvider getParentWrongProvider
     */
    public function testGetParentWrong($idPerson, $roleParent)
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.parent"), [
                "person_id" => $idPerson,
                "parent_role" => $roleParent
            ]);
        $response->assertStatus(302);
    }

    public function getParentWrongProvider(): array
    {
        return [
            ["blabla", null],
            [null, "one"],
        ];
    }

    public function testGetMarriageSuccess()
    {
        $this->seed();

        $personId = PeopleEloquentModel::pluck("id");
        $genderId = GenderEloquentModel::pluck("id");
        $roleMarriage = MarriageRoleEloquentModel::pluck("id");

        for ($i = 0; $i < 5; $i++) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.marriage"), [
                    "person_id" => $personId->random(),
                    "gender_id" => $genderId->random(),
                    "role_soulmate" => $roleMarriage->random()
                ]);
            $response->assertStatus(200);
        }
    }

    /**
     * @dataProvider getMarriageWrongProvider
     */
    public function testGetMarriageWrong($personId, $genderId, $roleMarriage)
    {
        $this->seed();

        $response = $this->actingAs($this->getAdmim())
            ->withSession(['banned' => false])
            ->post(route("partials.person.marriage"), [
                "person_id" => $personId,
                "gender_id" => $genderId,
                "role_soulmate" => $roleMarriage
            ]);
        $response->assertStatus(302);
    }

    public function getMarriageWrongProvider(): array
    {
        return [
            ["blabla", "man", null],
            [null, "woman", "one"],
        ];
    }

    public function testGetPhotoSuccess()
    {
        $this->seed();
        $this->setConfigFakeDisk();

        $people = PeopleEloquentModel::pluck("id");

        for ($i = 0; $i < 5; $i++) {
            Storage::fake("public");
            $file = UploadedFile::fake()->create("test.png");

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(route("partials.person.photo"), [
                    "person_id" => $people->random(),
                    "person_photo_file" => $file,
                ]);
            $response->assertStatus(200);

            $path = Storage::disk("public")->path("photo_temp") . "/" . $file->hashName();
            $this->assertFileExists($path);

            File::delete($path);
        }
    }
}
