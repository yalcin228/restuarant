<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMenuItemRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'is_stock'          => 'required|in:1,2',
            'stock'             => 'required_if:is_stock,1|integer|min:0',
            'type'              => 'required|integer|in:1,2',
            'stock_tracking'    => 'required|in:1,2',
            'ordinal_number'    => 'required|integer|min:1',
            'menu_id'           => 'required|exists:menus,id',
            'order_start_time'  => 'required|date_format:H:i',
            'order_end_time'    => 'required|date_format:H:i|after:order_start_time',
        ];
    }
}
