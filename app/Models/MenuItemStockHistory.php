<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemStockHistory extends Model
{
    use HasFactory;

    protected $fillable = ['menu_item_id', 'type', 'note', 'quantity'];
}
