<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTableRequest extends FormRequest
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
            'name'       => ['required','string','max:255', Rule::unique('tables')->where(function ($query) {
                return $query->where('restaurant_id', auth()->user()->restaurant_id);
            }),],
            'category_name'    => 'sometimes|string|max:255', 
            'ordinal_number'   => 'required|integer',
        ];
    }
}
