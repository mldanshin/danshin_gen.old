<?php

namespace Tests\Unit\Models\Person\Editable\Request;

use App\Models\Person\Editable\Request\ParentModel as ParentModel;
use PHPUnit\Framework\TestCase;

final class ParentModelTest extends TestCase
{
    /**
     * @dataProvider createProvider
     */
    public function testCreate(int $person, int $role): void
    {
        $model = new ParentModel($person, $role);

        $this->assertInstanceOf(ParentModel::class, $model);
        $this->assertEquals($person, $model->getPerson());
        $this->assertEquals($role, $model->getRole());
    }

    public function createProvider(): array
    {
        return [
            [10, 2],
            [123, 98]
        ];
    }
}
