<?php

namespace App\Http\Requests\People;

use App\Repositories\People\Ordering\Map;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class FilterOrderingRequest extends FormRequest
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
     * @return array|mixed[]
     */
    public function rules(Map $map): array
    {
        return [
            "people_search" => "nullable|string",
            "people_order" => [
                "nullable",
                "integer",
                Rule::in($map->getKeys())
            ],
        ];
    }
}
