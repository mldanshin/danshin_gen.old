<?php

namespace Tests\Feature\Models\Eloquent;

use App\Models\Eloquent\PersonRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class PersonRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerGetInstanceOrDefaultSuccess
     */
    public function testGetInstanceOrDefaultSuccess(
        int $personId,
        callable $callbackExpected
    ): void {
        $this->seed();

        $this->assertEquals($callbackExpected(), PersonRole::getInstanceOrDefault($personId)->role_id);
    }

    /**
     * @return array[]
     */
    public function providerGetInstanceOrDefaultSuccess(): array
    {
        return [
            [4, fn() => 1],
            [5, fn() => 1],
            [6, fn() => 3],
            [8, fn() => 2],
            [1, fn() => config("auth.person_role_default")],
            [2, fn() => config("auth.person_role_default")],
            [3, fn() => config("auth.person_role_default")],
        ];
    }

    /**
     * @dataProvider providerGetInstanceOrDefaultWrong
     */
    public function testGetInstanceOrDefaultWrong(int $personId): void
    {
        $this->seed();

        config(["auth.person_role_default" => -1]);

        try {
            PersonRole::getInstanceOrDefault($personId);
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
            $this->assertEquals(
                "The role_id=-1 is missing from the database table people_role",
                $e->getMessage()
            );
        }
    }

    /**
     * @return array[]
     */
    public function providerGetInstanceOrDefaultWrong(): array
    {
        return [
            [1],
            [2],
            [3],
        ];
    }
}
