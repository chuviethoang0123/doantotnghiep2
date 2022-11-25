<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouse = new Warehouse();
        $warehouse->product_id = 1;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 2;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 3;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 4;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 5;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 6;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 7;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 8;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 9;
        $warehouse->quantity = 10;
        $warehouse->save();

        $warehouse = new Warehouse();
        $warehouse->product_id = 10;
        $warehouse->quantity = 10;
        $warehouse->save();
    }
}
