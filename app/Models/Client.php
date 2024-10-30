<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory ,SoftDeletes;
    
    public $guarded = [];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    
    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
