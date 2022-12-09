<?php

namespace App\Http\Requests\Person\Partials;

use Illuminate\Foundation\Http\FormRequest;

final class ParentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array|string[]
     */
    public function rules(): array
    {
        return [
            "person_id" => "required|integer",
            "parent_role" => "required|integer|exists:parent_roles,id"
        ];
    }
}
