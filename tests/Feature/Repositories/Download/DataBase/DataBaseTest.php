<?php

namespace Tests\Feature\Repositories\Download\DataBase;

use Tests\TestCase;

final class DataBaseTest extends TestCase
{
    public function testCreateDefiniteFileSystem(): void
    {
        $this->markTestSkipped(
            "Тест пропущен так как тестирование идёт через sqlite в памяти, дамп которого не смог получить,
                а писать в рабочем коде получение дампа только из-за теста считаю лишним"
        );
    }
}
