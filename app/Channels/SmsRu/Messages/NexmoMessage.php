<?php

namespace App\Channels\SmsRu\Messages;

use Illuminate\Notifications\Messages\SimpleMessage;

class NexmoMessage extends SimpleMessage
{
    public string $content;

    public function content(string $string): self
    {
        $this->content = $string;

        return $this;
    }
}
