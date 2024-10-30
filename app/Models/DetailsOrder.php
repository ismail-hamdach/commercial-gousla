<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailsOrder extends Model
{
    use HasFactory,SoftDeletes;
    public $guarded = [];
    
    protected $table = 'details_orders';

    
    public function Order()
    {
        return $this->hasOne(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
