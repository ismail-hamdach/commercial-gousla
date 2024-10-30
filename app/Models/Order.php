<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates=['deleted_at'];
    protected $guarded = [];

    public function DetailsOrdes()
    {
        return $this->hasMany(DetailsOrder::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
