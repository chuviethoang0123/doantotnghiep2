<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

class Order extends Model
{ 
	protected $fillable = ['user_id','code','voucher_id','order_time','order_total_money','profile_id','action','is_payment'];
    public $table = "order";
    protected $primaryKey = 'id';

    public function profile() {
    	return $this->belongsTo(Profile::class, 'id', 'order_id');
    }
}
