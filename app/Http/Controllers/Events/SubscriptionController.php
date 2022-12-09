<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\Subscription\DeleteRequest;
use App\Http\Requests\Events\Subscription\StoreRequest;
use App\Repositories\Events\Subscription as SubscriptionRepository;
use App\Services\Events\ListenerTelegramBot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class SubscriptionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();
        $repository = new SubscriptionRepository($user);

        if ($repository->isSubscription()) {
            return redirect()->route("partials.events.subscription.edit");
        } else {
            return view("partials.events.subscription.create", [
                "userId" => $user->id,
                "code" => $repository->generateConfirmCode()
            ]);
        }
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $listener = new ListenerTelegramBot($request->code);
        $telegramUser = $listener->run();

        if ($telegramUser === null) {
            return response()->json([
                "status" => 0,
                "message" => __("events.subscription.crud.message.error.store")
            ]);
        } else {
            $user = Auth::user();
            $subscriptionRepository = new SubscriptionRepository($user);
            $subscriptionRepository->create($telegramUser);
            return response()->json([
                "status" => 1,
                "message" => __("events.subscription.crud.message.ok.store")
            ]);
        }
    }

    public function edit(): View|RedirectResponse
    {
        $user = Auth::user();
        $repository = new SubscriptionRepository($user);

        if ($repository->isSubscription()) {
            return view("partials.events.subscription.edit", [
                "user" => $user
            ]);
        } else {
            return redirect()->route("partials.events.subscription.create");
        }
    }

    public function delete(DeleteRequest $request): JsonResponse
    {
        $user = Auth::user();
        $repository = new SubscriptionRepository($user);
        $repository->deleteSubscriberEvent();

        return response()->json([
            "status" => 1,
            "message" => __("events.subscription.crud.message.ok.delete")
        ]);
    }
}
