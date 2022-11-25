<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function ( Blueprint $table ) {
            $table->bigIncrements('id');
            
            // The voucher code
            $table->string('code')->nullable();

            $table->string('image')->nullable();

            $table->text('name')->nullablde();
        
            // The description of the voucher - Not necessary 
            $table->text('description')->nullable();
        
            // số lượt đã sử dụng
            $table->integer('uses')->unsigned()->nullable();
        
            // số lượng max voucher phát ra
            $table->integer('max_uses')->unsigned()->nullable();
        
            // người dùng có thể sử dụng tối đa bao nhiêu lần voucher
            $table->integer('max_uses_user')->unsigned()->nullable();

            // áp dụng cho đơn hàng tối thiểu
            $table->bigInteger('minimum_order');
        
            // số tiền chiết khấu tối đa
            $table->integer('discount_amount');
        
            // phần trăm 
            $table->integer('percentage');
            
            // thòi gian bắt đầu
            $table->timestamp('starts_at')->nullable();
        
            // thời gian kết thúc
            $table->timestamp('expires_at')->nullable();
        
            // You know what this is...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
