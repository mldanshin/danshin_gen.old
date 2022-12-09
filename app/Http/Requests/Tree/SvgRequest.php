<?php

namespace App\Http\Requests\Tree;

use App\Exceptions\NotFoundException;
use App\Http\Validate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

final class SvgRequest extends FormRequest
{
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
            "person_id" => ["required", "string", "exists:people,id"],
            "width_screen" => ["required", "integer", "min:100"],
            "height_screen" => ["required", "integer", "min:100"]
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $this->validateParent($validator);
    }

    private function validateParent(Validator $validator): void
    {
        if (!empty($this->person_id)) {
            try {
                Validate::parent($this->person_id, $this->parent_id);
            } catch (NotFoundException) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'parent_id',
                        __("validation.person_parent_relation_wrong")
                    );
                });
            }
        }
    }
}
