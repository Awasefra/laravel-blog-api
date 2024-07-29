<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
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
        $pwdRules = [
            "required",
            Password::min(8)->letters()->mixedCase()->numbers()->symbols()
        ];
        return [
            "oldPassword" => $pwdRules,
            "newPassword" => $pwdRules,
            "newPasswordConfirmation" => [
                ...$pwdRules,
                "same:newPassword"
            ]
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
