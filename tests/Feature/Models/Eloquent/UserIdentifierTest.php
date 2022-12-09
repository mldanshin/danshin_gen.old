<?php

namespace Tests\Feature\Models\Eloquent;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\UserIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserIdentifierTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider providerGetIdByContent
     */
    public function testGetIdByContent(string $content, ?UserIdentifierType $expectedId): void
    {
        $this->seed();

        $this->assertEquals($expectedId, UserIdentifier::getIdByContent($content));
    }

    /**
     * @return array[]
     */
    public function providerGetIdByContent(): array
    {
        return [
            ["", null],
            ["blabla", null],
            ["blabla@", null],
            ["blabla@ss.", null],
            ["blabla@.ru", null],
            ["blabla@.", null],
            ["@blabla.ru", null],
            ["mail@danshin.net", UserIdentifierType::EMAIL],
            ["oleg@blabla.net", UserIdentifierType::EMAIL],
            ["den.max@danshin.net", UserIdentifierType::EMAIL],
            ["999-888-11-11", null],
            ["+79998881111", null],
            ["0998881111", UserIdentifierType::PHONE],
            ["9998881111", UserIdentifierType::PHONE],
            ["9", UserIdentifierType::PHONE],
            ["988", UserIdentifierType::PHONE],
        ];
    }
}
