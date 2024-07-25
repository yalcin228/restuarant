<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['table_id', 'restaurant_id', 'status'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class)->withPivot('quantity', 'price');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
