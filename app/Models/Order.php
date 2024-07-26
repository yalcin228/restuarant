<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'order_type_id', 'customer_id', 'restaurant_id','total_price','status'];

    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }

    public function menuItemOrders()
    {
        return $this->hasMany(MenuItemOrder::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function getTimeSinceOrderAttribute()
    {
        $diffInMinutes = Carbon::parse($this->created_at)->diffInMinutes(now());

        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;

        $formattedTime = '';

        if ($hours > 0) {
            $formattedTime .= "{$hours} st ";
        }
        
        $formattedTime .= "{$minutes} dk";

        return $formattedTime;
    }
}
