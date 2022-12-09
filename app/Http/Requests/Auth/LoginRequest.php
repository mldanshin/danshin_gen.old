<?php

namespace App\Http\Requests\Auth;

use App\Models\Auth\UserIdentifierType;
use App\Models\Eloquent\Email as EmailModel;
use App\Models\Eloquent\Phone as PhoneModel;
use App\Models\Eloquent\UserIdentifier as UserIdentifierModel;
use App\Repositories\Auth\Identifier;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    private ?int $personId;

    public function __construct(private Identifier $repository)
    {
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        if (! Auth::attempt(["person_id" => $this->personId, "password" => $this->password], $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'identifier' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), config("auth.throttle"))) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'throttle' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('identifier')) . '|' . $this->ip();
    }

    public function withValidator(Validator $validator): void
    {
        $this->ensureIsNotRateLimited();

        if (empty($this->identifier)) {
            return;
        }

        $identifierType = UserIdentifierModel::getIdByContent($this->identifier);
        if ($identifierType !== null) {
            $this->validateIdentifier($validator, $identifierType);
            $this->initializePerson($identifierType);
        } else {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identifier',
                    __("auth.failed")
                );
            });
        }

        RateLimiter::hit($this->throttleKey());
    }

    private function validateIdentifier(Validator $validator, UserIdentifierType $identifierType): void
    {
        switch ($identifierType) {
            case UserIdentifierType::EMAIL:
                $this->validateEmail($validator);
                break;
            case UserIdentifierType::PHONE:
                $this->validatePhone($validator);
                break;
            default:
                break;
        }
    }

    private function validateEmail(Validator $validator): void
    {
        if (!EmailModel::where("name", $this->identifier)->exists()) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identifier',
                    __("auth.failed")
                );
            });
        }
    }

    private function validatePhone(Validator $validator): void
    {
        if (!PhoneModel::where("name", $this->identifier)->exists()) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identifier',
                    __("auth.failed")
                );
            });
        }
    }

    private function initializePerson(UserIdentifierType $identifierType): void
    {
        $this->personId = $this->repository->getPersonId($identifierType, $this->identifier);
    }
}
