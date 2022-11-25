<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = "rate";

    public function product() {
    	return $this->belongsTo('App\Models\Product','product_id','id');
    }

    public function User() {
    	return $this->belongsTo('App\Models\User','user_id','id');
    }
}
