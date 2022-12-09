<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Exception;

final class SenderException extends Exception
{
    public function __construct(?string $message)
    {
        parent::__construct($message ?? "");
        Log::error($this->__toString());
    }

    public function render(): Response
    {
        return redirect()->route("register")->with("message", __("auth.confirm.code_send_impossible"));
    }
}
