<?php

namespace Tests\Unit\Models\Auth\Registration;

use App\Models\Auth\Registration\NotificationInfo;
use App\Services\NotificationTypes;
use PHPUnit\Framework\TestCase;

final class NotificationInfoTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(
        int $id,
        NotificationTypes $type,
        string $address
    ): void {
        $model = new NotificationInfo($id, $type, $address);

        $this->assertInstanceOf(NotificationInfo::class, $model);
        $this->assertEquals($id, $model->getIdUser());
        $this->assertEquals($type, $model->getType());
        $this->assertEquals($address, $model->getAddress());
    }

    public function createProvider(): array
    {
        return [
            [1, NotificationTypes::PHONE, "mail@danshin.net"],
            [4, NotificationTypes::EMAIL, "9992221144"],
        ];
    }
}
