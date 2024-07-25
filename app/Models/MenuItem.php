<?php

namespace App\Models;

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
        'type', 
        'stock_tracking', 
        'is_stock',
        'ordinal_number', 
        'order_start_time', 
        'order_end_time'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
