<?php

namespace App\Http\Requests\Events\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class StoreRequest extends FormRequest
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
            "user_id" => [
                'required',
                'string',
                'exists:users,id',
                Rule::in([Auth::id()]),
                'unique:subscribers_events,user_id'
            ],
            "code" => ['required', 'string']
        ];
    }
}
