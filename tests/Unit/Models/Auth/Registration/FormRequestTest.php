<?php

namespace Tests\Unit\Models\Auth\Registration;

use App\Models\Auth\UserIdentifierType;
use App\Models\Auth\Registration\FormRequest;
use PHPUnit\Framework\TestCase;

final class FormRequestTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        UserIdentifierType $identifierType,
        string $identifier,
        string $password
    ): void {
        $model = new FormRequest($identifierType, $identifier, $password);

        $this->assertInstanceOf(FormRequest::class, $model);
        $this->assertEquals($identifierType, $model->getIdentifierType());
        $this->assertEquals($identifier, $model->getIdentifier());
        $this->assertEquals($password, $model->getPassword());
    }

    public function createProvider(): array
    {
        return [
            [UserIdentifierType::EMAIL, "9998881111", "secret"],
            [UserIdentifierType::PHONE, "mail@danshin.net", "top_secret"],
        ];
    }
}
