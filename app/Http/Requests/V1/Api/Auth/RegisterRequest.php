<?php

namespace App\Http\Requests\V1\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'            => ['required','string','max:255'],
            'last_name'             => ['required','string','max:255'],
            'phone'                 => ['required','string','unique:users,phone'],
            'type_id'               => ['required','exists:types,id'],
            'email'                 => ['required','unique:users,email'],
            'password'              => ['required','string'],
        ];
    }
}
