<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'role' => 'admin',
        ]);
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:8',
            'role'                  => 'required|string|max:255',
            'passowrd_confirmation' => 'required|same:password',
            'restaurant_id'         => 'required|exists:restaurants,id',
            'duration'              => 'required|integer|in:1,10,30,365',
        ];
    }
}
