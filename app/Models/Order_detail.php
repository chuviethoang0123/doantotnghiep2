<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{

    // use HasFactory;
    protected $fillable = ['order_id','product_id','quantity','detail_amount'];
    public $table = "order_detail";
    protected $primaryKey = 'id';
}
