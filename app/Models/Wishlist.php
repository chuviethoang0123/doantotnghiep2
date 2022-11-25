<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
	protected $fillable = ['id','user_id','product_id','quantity'];
    public $table = "wishlist";
    protected $primaryKey = 'id';
}
