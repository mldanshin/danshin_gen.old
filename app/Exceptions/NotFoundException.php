<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Exception;

final class NotFoundException extends Exception
{
    public function __construct(?string $message)
    {
        parent::__construct($message ?? "");
        Log::info($this->__toString());
    }

    public function render(): Response
    {
        return abort(404);
    }
}
