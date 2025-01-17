<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['description', 'amount', 'date', 'customer_id', 'type'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
