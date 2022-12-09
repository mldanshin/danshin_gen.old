<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Repositories\Events\Events as Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class EventsController extends Controller
{
    public function show(Repository $repository): View
    {
        return view("partials.events.events", [
            "events" => $repository->get(),
            "user" => Auth::user()
        ]);
    }
}
