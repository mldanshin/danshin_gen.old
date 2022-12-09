<?php

namespace App\Http\Requests\Auth;

use App\Channels\SmsRu\ValidatorPhoneNumber as ValidatorPhoneNumberSmsRu;
use App\Models\Auth\UserIdentifierType;
use App\Models\Auth\Registration\FormRequest as RequestModel;
use App\Models\Eloquent\Email as EmailModel;
use App\Models\Eloquent\Phone as PhoneModel;
use App\Models\Eloquent\User as UserModel;
use App\Models\Eloquent\UserIdentifier as UserIdentifierModel;
use App\Models\Eloquent\UserUnconfirmed as UserUnconfirmedModel;
use App\Repositories\Auth\Identifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rules;

final class RegistrationFormRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    private ?UserIdentifierType $identifierType;

    public function __construct(
        private Identifier $repository,
        private ValidatorPhoneNumberSmsRu $validatorPhoneNumberSmsRu
    ) {
        $this->redirect = route("register");
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
            "identifier" => ['required', 'string'],
            "password" => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    /**
     * @throws \Exception
     */
    public function getModel(): RequestModel
    {
        if ($this->identifierType === null) {
            throw new \Exception("IdentifierType is null.");
        }

        return new RequestModel(
            $this->identifierType,
            $this->identifier,
            $this->password
        );
    }

    public function withValidator(Validator $validator): void
    {
        if (!empty($this->identifier)) {
            $this->initializeIdentifierType();

            if ($this->identifierType !== null) {
                $this->validateIdentifier($validator);
                $this->validateUserExist($validator);
                $this->validateUserUnconfirmedExist($validator);
            } else {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'identifier',
                        __("auth.identifier_incorrect")
                    );
                });
            }
        }
    }

    private function initializeIdentifierType(): void
    {
        $this->identifierType = UserIdentifierModel::getIdByContent($this->identifier);
    }

    private function validateIdentifier(Validator $validator): void
    {
        switch ($this->identifierType) {
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
                    __("auth.identifier_no_exists")
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
                    __("auth.identifier_no_exists")
                );
            });
        }

        if (!$this->validatorPhoneNumberSmsRu->verifyCount($this->identifier)) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'identifier',
                    __("smsru.validation.phone.count", ["count" => config("services.sms_api.number_digits")])
                );
            });
        }
    }

    private function validateUserExist(Validator $validator): void
    {
        if ($this->identifierType === null) {
            return;
        }

        $personId = $this->repository->getPersonId($this->identifierType, $this->identifier);
        if ($personId === null) {
            return;
        }

        if (UserModel::where("person_id", $personId)->exists()) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "identifier",
                    __("auth.user_exist")
                );
            });
        }
    }

    private function validateUserUnconfirmedExist(Validator $validator): void
    {
        $user = UserUnconfirmedModel::where("identifier", $this->identifier)->first();
        if ($user !== null) {
            $this->redirect = route("register.confirmation", [$user->id]);
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    "identifier",
                    __("auth.confirm.user_unconfirmed_exists")
                );
                session(["message" => __("auth.confirm.user_unconfirmed_exists")]);
            });
        }
    }
}
