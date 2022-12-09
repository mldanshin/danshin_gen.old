<?php

namespace App\Http\Requests\Auth;

use App\Repositories\Auth\Registration\Registration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class RegistrationRepeatConfirmationRequest extends FormRequest
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
            "id" => ['required', 'string', 'exists:users_unconfirmed,id']
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateRepeatTimestamp($validator);
    }

    private function validateRepeatTimestamp(Validator $validator): void
    {
        if (empty($this->id)) {
            return;
        }

        if ($this->registrationUser->verifyRepeatTimestamp($this->id) === false) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "repeate_timestamp",
                    __("auth.confirm.repeate_timestamp_not_reached")
                );
            });
        }
    }
}
