<?php

namespace App\Http\Requests\Events\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class DeleteRequest extends FormRequest
{
    public function __construct()
    {
        $this->redirect = route("partials.events.subscription.edit");
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
            "user_id" => ['required', 'string', 'exists:subscribers_events,user_id', Rule::in([Auth::id()])]
        ];
    }
}
