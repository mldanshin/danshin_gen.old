<?php

namespace Tests\Unit\Models\Person\Editable\Request;

use App\Models\Person\Editable\Request\Marriage as MarriageModel;
use PHPUnit\Framework\TestCase;

final class MarriageTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(int $roleCurrent, int $soulmate, int $roleSoulmate): void
    {
        $model = new MarriageModel($roleCurrent, $soulmate, $roleSoulmate);

        $this->assertInstanceOf(MarriageModel::class, $model);
        $this->assertEquals($roleCurrent, $model->getRoleCurrent());
        $this->assertEquals($soulmate, $model->getSoulmate());
        $this->assertEquals($roleSoulmate, $model->getRoleSoulmate());
    }

    public function createProvider(): array
    {
        return [
            [2, 10, 2],
            [3, 123, 34]
        ];
    }
}
