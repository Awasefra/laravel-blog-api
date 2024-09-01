<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostRequest extends FormRequest
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
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'image' => $this->isMethod('put')
                ? 'image|max:2048'
                : 'required|image|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $errorMessages = [];

        foreach ($errors->messages() as $field => $messages) {
            foreach ($messages as $message) {
                $errorMessages[$field] = $message;
            }
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'code' => 422,
            'message' => $errorMessages,
            'data' => null
        ], 422));
    }
}
