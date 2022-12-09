<?php

namespace Tests\Feature\Support;

use App\Models\Eloquent\UserUnconfirmed as UserUnconfirmedModel;
use App\Support\UserUnconfirmed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserUnconfirmedTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): UserUnconfirmed
    {
        $obj = new UserUnconfirmed();
        $this->assertInstanceOf(UserUnconfirmed::class, $obj);
        return $obj;
    }

    /**
     * @depends testCreate
     */
    public function testDelete(UserUnconfirmed $obj): void
    {
        $this->seed();

        $this->prepareForDelete();

        $obj->delete();

        $this->assertCount(3, UserUnconfirmedModel::get());
    }

    private function prepareForDelete(): void
    {
        $now = time();

        $timestampArray = [
            $now + 120,
            $now,
            $now - 120,
            $now - 96400,
            $now - 196400,
        ];

        $users = UserUnconfirmedModel::get();
        $i = 0;
        foreach ($users as $user) {
            $user->timestamp = (string)$timestampArray[$i];
            $user->save();

            $i++;
        }
    }
}
