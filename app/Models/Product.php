<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "product";
    protected $primaryKey = 'id';

    public function rate() {
    	return $this->hasMany('App\Models\Rate','product_id','id');
    }
}
