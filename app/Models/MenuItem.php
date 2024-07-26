<?php

namespace App\Models;

use App\Http\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name', 
        'photo', 
        'price', 
        'stock',
        'menu_id', 
        'show_qr',
        'type', 
        'stock_tracking_quantity',
        'is_stock_tracking', 
        'is_stock',
        'order_start_time', 
        'order_end_time'
    ];

    protected $casts = [
        'type' => ProductTypeEnum::class,
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(MenuItemStockHistory::class);
    }
}
