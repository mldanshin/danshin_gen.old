<?php

namespace App\Http\Requests\Person\Partials;

use App\Models\Person\Editable\UploadedPhoto as PhotoModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Http\FormRequest;

final class PhotoRequest extends FormRequest
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
            "person_photo_file" => "required|image"
        ];
    }

    /**
    * @throws \Exception
    */
    public function getModel(): PhotoModel
    {
        $requestFile = $this->file("person_photo_file");
        if (!($requestFile instanceof UploadedFile)) {
            throw new \Exception("invalid input data type");
        }

        return new PhotoModel($this->person_id, $requestFile);
    }
}
