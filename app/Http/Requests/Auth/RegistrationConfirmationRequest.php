<?php

namespace App\Http\Requests\Auth;

use App\Models\Eloquent\UserUnconfirmed as UserUnconfirmedEloquentModel;
use App\Repositories\Auth\Registration\Registration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class RegistrationConfirmationRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function __construct(private Registration $registrationUser)
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
            "id" => ['required', 'string', 'exists:users_unconfirmed,id'],
            "code" => ['required', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $user = UserUnconfirmedEloquentModel::find($this->id);

        if (!empty($this->id)) {
            $this->redirect = route("register.confirmation", [$this->id]);
        }

        if ($user !== null) {
            $this->validateTime($validator, $user);
            $this->validateAttempts($validator, $user);
            $this->validateCode($validator, $user);
        }
    }

    private function validateTime(Validator $validator, UserUnconfirmedEloquentModel $user): void
    {
        if ($this->registrationUser->verifyTime((int)$user->timestamp) === false) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "time",
                    __("auth.confirm.time_over")
                );
            });
        }
    }

    private function validateAttempts(Validator $validator, UserUnconfirmedEloquentModel $user): void
    {
        if ($this->registrationUser->verifyAttempts($user->attempts) === false) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "attempts",
                    __("auth.confirm.attempts_ended")
                );
            });
        }
    }

    private function validateCode(Validator $validator, UserUnconfirmedEloquentModel $user): void
    {
        if (empty($this->code)) {
            return;
        }

        if ($this->registrationUser->verifyCode($user->code, $this->code) === false) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "code",
                    __("auth.confirm.code_error")
                );
            });
            $this->registrationUser->reduceAttempts($user);
        }
    }
}
