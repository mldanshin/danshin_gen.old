<?php

namespace Tests\Feature\Repositories\Auth;

use App\Models\Auth\UserIdentifierType;
use App\Repositories\Auth\Identifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class IdentifierTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): Identifier
    {
        $repository = new Identifier();
        $this->assertInstanceOf(Identifier::class, $repository);
        return $repository;
    }

    /**
     * @depends testCreate
     * @dataProvider providerGetPersonId
     */
    public function testGetPersonId(
        UserIdentifierType $identifierType,
        string $contentIdentifier,
        ?int $idPerson,
        Identifier $repository
    ): void {
        $this->seed();

        $this->assertEquals($idPerson, $repository->getPersonId($identifierType, $contentIdentifier));
    }

    /**
     * @return array[]
     */
    public function providerGetPersonId(): array
    {
        return [
            [UserIdentifierType::PHONE, "9991112222", 5],
            [UserIdentifierType::EMAIL, "mail@danshin.net", 5],
            [UserIdentifierType::PHONE, "mail@danshin.net", null],
            [UserIdentifierType::PHONE, "9992222222", 6],
            [UserIdentifierType::PHONE, "3456789", null],
            [UserIdentifierType::PHONE, "", null],
            [UserIdentifierType::EMAIL, "", null],
            [UserIdentifierType::EMAIL, "9991112222", null],
            [UserIdentifierType::EMAIL, "mail@blabla.net", null],
        ];
    }
}
