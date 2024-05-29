<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;


class ActionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:actions,name',
        ];
    }

    public function failedValidation(Validator $validator){

        // send error message
        Helper::sendError('validation error', $validator->errors());
    }
}
