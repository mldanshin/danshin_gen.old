<?php

namespace Tests\Feature\Channels\SmsRu\Messages;

use App\Channels\SmsRu\Messages\NexmoMessage;
use Tests\TestCase;

final class NexmoMessageTest extends TestCase
{
    public function testCreate(): NexmoMessage
    {
        $obj = new NexmoMessage();
        $this->assertInstanceOf(NexmoMessage::class, $obj);

        return $obj;
    }

    /**
     * @depends testCreate
     * @dataProvider providerContent
     */
    public function testContent(string $content, NexmoMessage $obj): void
    {
        $res = $obj->content($content);

        $this->assertEquals($obj, $res);
    }

    /**
     * @return array[]
     */
    public function providerContent(): array
    {
        return [
            ["hello"],
            ["code 4567"]
        ];
    }
}
