<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOnlineOrderRequest extends FormRequest
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
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'note'            => 'sometimes|string|max:255',
            'menu_items'      => 'required|array',
            'menu_items.*.id' => 'required|exists:menu_items,id',
        ];
    }
}
