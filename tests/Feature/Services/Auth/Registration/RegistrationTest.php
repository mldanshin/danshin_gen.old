<?php

namespace Tests\Feature\Services\Auth\Registration;

use App\Models\Auth\UserIdentifierType;
use App\Models\Auth\Registration\FormRequest;
use App\Models\Auth\Registration\NotificationInfo;
use App\Models\Eloquent\UserUnconfirmed;
use App\Repositories\Auth\Identifier as IdentifierRepository;
use App\Repositories\Auth\Registration\Registration as RegistrationRepository;
use App\Services\Auth\Registration\Registration;
use App\Services\NotificationTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): Registration
    {
        $model = new Registration(new RegistrationRepository(new IdentifierRepository()));
        $this->assertInstanceOf(Registration::class, $model);
        return $model;
    }

    /**
     * @depends testCreate
     * @dataProvider providerSendFirstConfirmationCode
     */
    public function testSendFirstConfirmationCode(
        UserIdentifierType $identifierType,
        string $identifier,
        string $password,
        NotificationTypes $notificationType,
        Registration $object
    ): void {
        //preparation
        $this->seed();
        $address = $identifier;

        //testing
        $notificationInfo = $object->sendFirstConfirmationCode(new FormRequest($identifierType, $identifier, $password));

        //verify
        $user = UserUnconfirmed::where("identifier", $identifier)->first();
        $this->assertEquals(
            new NotificationInfo($user->id, $notificationType, $address),
            $notificationInfo
        );
    }

    /**
     * @return array[]
     */
    public function providerSendFirstConfirmationCode(): array
    {
        return [
            [UserIdentifierType::PHONE, "9995552222", "password", NotificationTypes::PHONE],
            [UserIdentifierType::PHONE, "9996662222", "password", NotificationTypes::PHONE],
            [UserIdentifierType::PHONE, "9997772222", "password", NotificationTypes::PHONE],
            [UserIdentifierType::PHONE, "9990002222", "password", NotificationTypes::PHONE],
            [UserIdentifierType::EMAIL, "mail@danshin.net", "password", NotificationTypes::EMAIL],
        ];
    }

    /**
     * @depends testCreate
     * @dataProvider providerSendRepeatConfirmationCode
     */
    public function testSendRepeatConfirmationCode(
        int $idUser,
        NotificationTypes $notificationType,
        Registration $object
    ): void {
        //preparation
        $this->seed();

        //testing
        $notificationInfo = $object->sendRepeatConfirmationCode($idUser);

        //verify
        $user = UserUnconfirmed::find($idUser); 
        $address = $user->identifier;

        $this->assertEquals(
            new NotificationInfo($user->id, $notificationType, $address),
            $notificationInfo
        );
    }

    /**
     * @return array[]
     */
    public function providerSendRepeatConfirmationCode(): array
    {
        return [
            [1, NotificationTypes::PHONE],
            [2, NotificationTypes::PHONE],
            [3, NotificationTypes::PHONE],
            [4, NotificationTypes::PHONE],
            [5, NotificationTypes::EMAIL],
        ];
    }
}
