<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Auth as AuthHelper;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\RegistrationFormRequest;
use App\Http\Requests\Auth\RegistrationConfirmationRequest;
use App\Http\Requests\Auth\RegistrationRepeatConfirmationRequest;
use App\Repositories\Auth\Registration\Registration as RegistrationRepository;
use App\Services\Auth\Registration\Registration as RegistrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class RegisteredUserController extends Controller
{
    public function __construct(
        private RegistrationRepository $repository,
        private RegistrationService $service
    ) {
    }

    public function create(): View
    {
        return view('auth.register');
    }

    public function handleForm(RegistrationFormRequest $request): RedirectResponse
    {
        $sender = $this->service->sendFirstConfirmationCode($request->getModel());
        return redirect()->route("register.confirmation", [$sender->getIdUser()])
            ->with("message", AuthHelper::getConfirmationInfoSender($sender->getType(), $sender->getAddress()));
    }

    public function createConfirmation(string $userId): RedirectResponse|View
    {
        $confirmationCode = $this->repository->getConfirmationCodeForm($userId);
        if ($confirmationCode === null) {
            $response = redirect()->route("register.confirmation-repeated", [$userId]);
            if (!empty(session("message"))) {
                $response->with("message", session("message"));
            }
            return $response;
        } else {
            return view("auth.confirmation-code", ["model" => $confirmationCode]);
        }
    }

    public function createRepeatConfirmation(string $userId): View
    {
        $user = $this->repository->getUserUnconfirmedOrFail($userId);
        return view("auth.repeated-code", [
            "userId" => $userId,
            "repeatTimestamp" => $this->repository->getRepeatTimestampInterval($user->repeat_timestamp)
        ]);
    }

    public function repeatConfirmation(RegistrationRepeatConfirmationRequest $request): RedirectResponse
    {
        $sender = $this->service->sendRepeatConfirmationCode($request->id);
        return redirect()->route("register.confirmation", [$sender->getIdUser()])
            ->with("message", AuthHelper::getConfirmationInfoSender($sender->getType(), $sender->getAddress()));
    }

    /**
     * @throws ValidationException
     */
    public function confirm(RegistrationConfirmationRequest $request): RedirectResponse
    {
        $user = $this->repository->confirmUser($request->id);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route("index");
    }
}
