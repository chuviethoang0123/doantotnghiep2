<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            SlideSeeder::class,
            WarehouseSeeder::class,
        ]);


        // data Product
        DB::table('product')->insert([
            'name' =>'Sản phẩm 1',
            'description' => 'aaaaaaaaaaaaaaaaa',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '1.jfif',
            'price' => 600000,
            'discount' => 15,
            'selling' => 100,
            'category_id' => 1,
            'brand_id' => 1,
            'width' => 1,
            'height' => 1,
            'length' => 1,
            'weight' => 1
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 2',
            'description' => 'bbbbbbbbbbbbbbbbb',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '2.jfif',
            'price' => 500000,
            'discount' => 5,
            'selling' => 90,
            'category_id' => 2,
            'brand_id' => 2,
            'width' => 2,
            'height' => 2,
            'length' => 2,
            'weight' => 2
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 3',
            'description' => 'ccccccccccccc',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '3.jfif',
            'price' => 400000,
            'discount' => 10,
            'selling' => 80,
            'category_id' => 3,
            'brand_id' => 3,
            'width' => 3,
            'height' => 3,
            'length' => 3,
            'weight' => 3
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 4',
            'description' => 'ddddddddddddd',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '4.jfif',
            'price' => 300000,
            'discount' => 15,
            'selling' => 70,
            'category_id' => 4,
            'brand_id' => 4,
            'width' => 4,
            'height' => 4,
            'length' => 4,
            'weight' => 4
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 5',
            'description' => 'eeeeeeeeeeeeeeeeee',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '5.jfif',
            'price' => 200000,
            'discount' => 15,
            'selling' => 60,
            'category_id' => 5,
            'brand_id' => 5,
            'width' => 5,
            'height' => 5,
            'length' => 5,
            'weight' => 5
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 6',
            'description' => 'ffffffffffffffffff',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '6.jfif',
            'price' => 100000,
            'discount' => 15,
            'selling' => 50,
            'category_id' => 6,
            'brand_id' => 6,
            'width' => 6,
            'height' => 6,
            'length' => 6,
            'weight' => 6
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 7',
            'description' => 'ffffffffffffffffff',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '7.jfif',
            'price' => 100000,
            'discount' => 15,
            'selling' => 50,
            'category_id' => 6,
            'brand_id' => 6,
            'width' => 6,
            'height' => 6,
            'length' => 6,
            'weight' => 6
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 8',
            'description' => 'ffffffffffffffffff',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '8.jpg',
            'price' => 100000,
            'discount' => 15,
            'selling' => 50,
            'category_id' => 6,
            'brand_id' => 6,
            'width' => 6,
            'height' => 6,
            'length' => 6,
            'weight' => 6
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 9',
            'description' => 'ffffffffffffffffff',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '9.jpg',
            'price' => 100000,
            'discount' => 15,
            'selling' => 50,
            'category_id' => 6,
            'brand_id' => 6,
            'width' => 6,
            'height' => 6,
            'length' => 6,
            'weight' => 6
        ]);
        DB::table('product')->insert([
            'name' =>'Sản phẩm 10',
            'description' => 'ffffffffffffffffff',
            'content' => 'Ligula ullamcorper malesuada proin libero nunc consequat interdum varius. Turpis egestas pretium aenean pharetra magna ac placerat. Sed egestas egestas fringilla phasellus faucibus scelerisque eleifend donec. Sed cras ornare arcu dui. Aliquam vestibulum morbi blandit cursus. Adipiscing elit ut aliquam purus sit amet. Aenean sed adipiscing diam donec adipiscing tristique risus nec. Ut etiam sit amet nisl purus in mollis. Eu mi bibendum neque egestas congue quisque egestas diam in. Pellentesque adipiscing commodo elit at imperdiet dui accumsan sit.',
            'image' => '10.jfif',
            'price' => 100000,
            'discount' => 15,
            'selling' => 50,
            'category_id' => 6,
            'brand_id' => 6,
            'width' => 6,
            'height' => 6,
            'length' => 6,
            'weight' => 6
        ]);

        // data voucher
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA03',
            'image' => '3.png',
            'name' => 'Giảm giá 3% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 3% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 200000,
            'discount_amount' => 50000,
            'percentage' => 5,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA10',
            'image' => '10.png',
            'name' => 'Giảm giá 10% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 10% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 500000,
            'discount_amount' => 60000,
            'percentage' => 10,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA15',
            'image' => '15.png',
            'name' => 'Giảm giá 15% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 15% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 700000,
            'discount_amount' => 70000,
            'percentage' => 15,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA20',
            'image' => '20.png',
            'name' => 'Giảm giá 20% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 20% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 1000000,
            'discount_amount' => 80000,
            'percentage' => 20,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA25',
            'image' => '25.png',
            'name' => 'Giảm giá 25% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 25% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 1000000,
            'discount_amount' => 80000,
            'percentage' => 20,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
        DB::table('vouchers')->insert([
            'code' =>'GIAMGIA03',
            'image' => '3.png',
            'name' => 'Giảm giá 3% cho tổng giá trị đơn hàng',
            'description' => 'Giảm giá 3% cho tổng giá trị đơn hàng',
            'uses' => 5,
            'max_uses' => 50,
            'max_uses_user' => 2,
            'minimum_order' => 1000000,
            'discount_amount' => 80000,
            'percentage' => 20,
            'starts_at' => '2022-02-24 00:00:00.000000',
            'expires_at' => '2022-03-24 00:00:00.000000'
        ]);
    }
}
