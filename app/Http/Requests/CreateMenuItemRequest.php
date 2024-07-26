<?php

namespace App\Http\Requests;

use App\Http\Enums\ProductTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'name'                      => 'required|string|max:255',
            'price'                     => 'required|numeric|min:0',
            'is_stock'                  => 'required|in:1,2',
            'stock'                     => 'required_if:is_stock,1|integer|min:0',
            'type'                      => ["required_if:is_stock,1",new Enum(ProductTypeEnum::class)],
            'show_qr'                   => 'required|integer|in:1,2',
            'is_stock_tracking'         => 'required|in:1,2',
            'stock_tracking_quantity'   => 'required_if:is_stock_tracking,1|numeric|min:0',
            'menu_id'                   => 'required|exists:menus,id',
            'order_start_time'          => 'required|date_format:H:i',
            'order_end_time'            => 'required|date_format:H:i|after:order_start_time',
        ];
    }
}
