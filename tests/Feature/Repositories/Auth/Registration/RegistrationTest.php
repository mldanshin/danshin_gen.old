<?php

namespace Tests\Feature\Repositories\Auth\Registration;

use App\Models\Auth\UserIdentifierType;
use App\Models\Auth\Registration\ConfirmationCodeForm;
use App\Models\Auth\Registration\FormRequest;
use App\Models\Eloquent\User;
use App\Models\Eloquent\UserUnconfirmed;
use App\Repositories\Auth\Identifier as IdentifierRepository;
use App\Repositories\Auth\Registration\Registration;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): Registration
    {
        $model = new Registration(new IdentifierRepository());
        $this->assertInstanceOf(Registration::class, $model);
        return $model;
    }

    /**
     * @depends testCreate
     * @dataProvider providerConfirmUser
     * 
     * @param int[] $missinUsersUnconfirmed
     */
    public function testConfirmUser(
        int $usersUnconfirmedId,
        int $personId,
        array $missinUsersUnconfirmed,
        Registration $object
    ): void {
        //preparation
        $this->seed();

        $usersUnconfirmed = UserUnconfirmed::find($usersUnconfirmedId);

        //testing
        $user = $object->confirmUser($usersUnconfirmedId);

        $this->assertEquals($usersUnconfirmed->password, $user->password);
        $this->assertEquals($personId, $user->person_id);
        $this->assertInstanceOf(User::class, $user);

        foreach ($missinUsersUnconfirmed as $missing) {
            $this->assertNull(UserUnconfirmed::find($missing));
        }
    }

    /**
     * @return array[]
     */
    public function providerConfirmUser(): array
    {
        return [
            [1, 14, [1]],
            [2, 15, [2, 3]],
            [3, 15, [2, 3]],
            [4, 17, [4]],
            [5, 18, [5]],
        ];
    }

    /**
     * @depends testCreate
     * @throws \Exception
     */
    public function testGetUserUnconfirmedOrFail(Registration $object): void
    {
        $this->seed();

        //exist
        $usersExpected = UserUnconfirmed::limit(5)->get();
        foreach ($usersExpected as $expected) {
            $actual = $object->getUserUnconfirmedOrFail($expected->id);

            $this->assertEquals($expected, $actual);
        }

        //not exist
        for ($i = 0; $i < 10; $i++) {
            $id = random_int(-200, -1);
            try {
                $object->getUserUnconfirmedOrFail($id);
            } catch (ModelNotFoundException $e) {
                $this->assertInstanceOf(ModelNotFoundException::class, $e);
            } catch (\Exception) {
                throw new \Exception();
            }
        }
    }

    /**
     * @depends testCreate
     * @dataProvider providerCreateUserUnconfirmed
     */
    public function testCreateUserUnconfirmed(FormRequest $request, Registration $object): void
    {
        $user = $object->createUserUnconfirmed($request);

        $this->assertEquals($request->getIdentifierType(), $user->getIdentifierType());
        $this->assertEquals($request->getIdentifier(), $user->identifier);
        $this->assertIsString($user->password);
        $this->assertIsString($user->timestamp);
        $this->assertEquals(config("auth.confirmation_user.attempts"), $user->attempts);
        $this->assertNotNull($user->code);
        $this->assertIsString($user->repeat_timestamp);
        $this->assertEquals(0, $user->repeat_attempts);
    }

    /**
     * @return array[]
     */
    public function providerCreateUserUnconfirmed(): array
    {
        return [
            [new FormRequest(UserIdentifierType::PHONE, "9008001111", "password")],
            [new FormRequest(UserIdentifierType::EMAIL, "ivan23@danshin.net", "password1")],
            [new FormRequest(UserIdentifierType::PHONE, "9007002222", "password1")],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerRepeatUserUnconfirmed
     */
    public function testRepeatUserUnconfirmed(int $idUser, Registration $object): void
    {
        $this->seed();

        $user = $object->repeatUserUnconfirmed($idUser);

        $this->assertInstanceOf(UserUnconfirmed::class, $user);
    }

    /**
     * @return array[]
     */
    public function providerRepeatUserUnconfirmed(): array
    {
        return [
            [1],
            [5],
            [3],
            [2],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetConfirmationCodeFormSuccess
     */
    public function testGetConfirmationCodeFormSuccess(
        string $userId,
        Registration $object
    ): void {
        $this->seed();

        $user = UserUnconfirmed::find($userId);
        $expected = new ConfirmationCodeForm(
            $user->id,
            $user->attempts,
            $user->timestamp - time(),
            $user->repeat_timestamp - time()
        );
        $actual = $object->getConfirmationCodeForm($userId);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array[]
     */
    public function providerGetConfirmationCodeFormSuccess(): array
    {
        return [
            ["4"],
            ["5"],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetConfirmationCodeFormWrong
     */
    public function testGetConfirmationCodeFormWrong(
        string $userId,
        Registration $object
    ): void {
        $this->seed();

        $this->assertNull($object->getConfirmationCodeForm($userId));
    }

    /**
     * @return array[]
     */
    public function providerGetConfirmationCodeFormWrong(): array
    {
        return [
            ["1"],
            ["2"],
            ["3"],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerVerifyRepeatTimestamp
     */
    public function testVerifyRepeatTimestamp(
        string $userId,
        bool $expected,
        Registration $object
    ): void {
        $this->seed();

        $this->assertEquals($expected, $object->verifyRepeatTimestamp($userId));
    }

    /**
     * @return array[]
     */
    public function providerVerifyRepeatTimestamp(): array
    {
        return [
            ["-1", false],
            ["0", false],
            ["1", false],
            ["4", false],
            ["2", true],
            ["3", true],
        ];
    }

    /**
     * @depends testCreate
     */
    public function testReduceAttempts(Registration $object): void
    {
        $this->seed();

        $users = UserUnconfirmed::get();

        foreach ($users as $user) {
            $object->reduceAttempts($user);
            $this->assertEquals($user->attempts, UserUnconfirmed::where("id", $user->id)->value("attempts"));
        }
    }

    /**
     * @depends testCreate
     */
    public function testGetRepeatTimestampInterval(Registration $object): void
    {
        $this->seed();

        $users = UserUnconfirmed::get();

        foreach ($users as $user) {
            $expected = $user->repeat_timestamp - time();
            $actual = $object->getRepeatTimestampInterval($user->repeat_timestamp);
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @depends testCreate
     * @dataProvider providerVerifyTime
     */
    public function testVerifyTime(
        int $timestamp,
        bool $expected,
        Registration $object
    ): void {
        $this->seed();

        $this->assertEquals($expected, $object->verifyTime($timestamp));
    }

    /**
     * @return array[]
     */
    public function providerVerifyTime(): array
    {
        return [
            [time() + 1200, true],
            [time() + 12000, true],
            [time() + 2300, true],
            [time() - 1200, false],
            [time() - 120, false],
            [time() - 12000, false],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerVerifyAttempts
     */
    public function testVerifyAttempts(
        int $attempts,
        bool $expected,
        Registration $object
    ): void {
        $this->seed();

        $this->assertEquals($expected, $object->verifyAttempts($attempts));
    }

    /**
     * @return array[]
     */
    public function providerVerifyAttempts(): array
    {
        return [
            [0, false],
            [-1, false],
            [1, true],
            [2, true],
            [19, true],
            [100, true],
        ];
    }
}
