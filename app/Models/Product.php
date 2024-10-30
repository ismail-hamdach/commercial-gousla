<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];


    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function detailsOrder(){
        return $this->belongsToMany(Order::class);
    }
   
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
