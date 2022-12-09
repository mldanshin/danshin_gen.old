<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogRequest;
use Illuminate\Support\Facades\Log;

final class LogController extends Controller
{
    public function __invoke(LogRequest $request): void
    {
        Log::error("Error frontend. " . $request->message);
    }
}
