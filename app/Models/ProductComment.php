<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    use HasFactory;
    protected $table = "product_comment";
    protected $fillable = ['user_id','product_id','content'];

    public function product() {
    	return $this->belongsTo('App\Models\Product','id','product_id');
    }

    public function User() {
    	return $this->belongsTo('App\Models\User','user_id','id');
    }
}
