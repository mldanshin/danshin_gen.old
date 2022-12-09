<?php

namespace Tests\Feature\Services\Events;

use App\Models\Eloquent\User;
use App\Models\Events\Birth;
use App\Models\Events\Events as Model;
use App\Models\Events\Person;
use App\Services\NotificationTypes;
use App\Services\Events\NotificationSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class NotificationSenderTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate(): void
    {
        $this->seed();

        $object = new NotificationSender(
            NotificationTypes::TELEGRAM,
            $this->providerModel(),
            User::has("telegram")->get()
        );

        $this->assertInstanceOf(NotificationSender::class, $object);
    }

    public function testSenderSuccess(): void
    {
        $this->seed();

        $object = new NotificationSender(
            NotificationTypes::TELEGRAM,
            $this->providerModel(),
            User::has("telegram")->get()
        );

        Notification::fake();

        $res = $object->send();
        $this->assertTrue($res);
    }

    public function testSenderWrongType(): void
    {
        $this->seed();

        $object = new NotificationSender(
            NotificationTypes::PHONE,
            $this->providerModel(),
            User::has("phone")->get()
        );

        try {
            $object->send();
        } catch (\Exception $e) {
            $this->assertEquals("The sender is missing", $e->getMessage());
        }
    }

    private function providerModel(): Model
    {
        return new Model(
            collect(),
            collect([
                new Birth(
                    "2000-10-01",
                    new Person(1, "Ivanov", "Ivan", "Ivanovich"),
                    new \DateInterval("P20Y")
                    )
            ]),
            collect()
        );
    }
}
