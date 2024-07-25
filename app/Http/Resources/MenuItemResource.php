<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'                => $this->id,
            'name'              => $this->name,
            'price'             => $this->price,
            'stock'             => $this->stock,
            'is_stock'          => $this->is_stock,
            'menu'              => $this->menu->name,
            'type'              => $this->type,
            'stock_tracking'    => $this->stock_tracking,
            'ordinal_number'    => $this->ordinal_number,
            'order_start_time'  => $this->order_start_time,
            'order_end_time'    => $this->order_end_time
        ];

        if(isset($this->photo))
        {
            $data['photo'] = asset('/storage/products/'.$this->photo);
        }

        return $data;
    }
}
