<?php

namespace Tests\Feature\Http\Controllers\Person;

use App\Models\Eloquent\People as PeopleEloquentModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DataProvider\Photo as PhotoDataProvider;
use Tests\DataProvider\User as UserDataProvider;
use Tests\TestCase;

final class ResourceControllerUploadedPhotoTest extends TestCase
{
    use FormData;
    use PhotoDataProvider;
    use RefreshDatabase;
    use UserDataProvider;

    public function testSuccess(): void
    {
        //preparation
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        //testing
        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            Storage::fake('public');
            $file = UploadedFile::fake()->create('avatar.jpg', 100);

            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(
                    route("partials.person.photo"),
                    [
                        "person_id" => $person->id,
                        "person_photo_file" => $file
                    ]
                );
            $response->assertStatus(200);
        }
    }

    public function testWrong(): void
    {
        //preparation
        $this->seed();
        $this->seedPhoto();
        $this->setConfigFakeDisk();

        //testing
        $people = PeopleEloquentModel::limit(10)->get();
        foreach ($people as $person) {
            $response = $this->actingAs($this->getAdmim())
                ->withSession(['banned' => false])
                ->post(
                    route("partials.person.photo"),
                    [
                        "person_id" => $person->id,
                        "person_photo_file" => "dddd.jpg"
                    ]
                );
            $response->assertStatus(302);
        }
    }
}
