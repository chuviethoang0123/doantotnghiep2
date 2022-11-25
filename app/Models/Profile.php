<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $table = "profile";
    protected $fillable = ['order_id','name','email','phone','province_id','district_id','ward_id','note'];
    protected $primaryKey = 'id';
    // protected $guarded = [];  

    public function order()
    {
        return $this->hasOne(Order::class, 'order_id', 'id');
    }
}
