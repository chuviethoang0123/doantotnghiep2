<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVoucher extends Model
{
    use HasFactory;
    public $table = "user_voucher";
    protected $primaryKey = 'id';
    protected $guarded = [];  
    public $timestamps = false;
}
