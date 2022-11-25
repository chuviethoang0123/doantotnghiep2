<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = 'Đồ gia dụng';
        $category->save();

        $category = new Category();
        $category->name = 'Mâm cơm mẫu';
        $category->save();

        $category = new Category();
        $category->name = 'Thực phẩm sẵn';
        $category->save();

        $category = new Category();
        $category->name = 'Rau củ quả';
        $category->save();

        $category = new Category();
        $category->name = 'Thịt cá trứng';
        $category->save();

        $category = new Category();
        $category->name = 'Thủy hải sản';
        $category->save();

        $category = new Category();
        $category->name = 'Thực phẩm đông lạnh';
        $category->save();

        $category = new Category();
        $category->name = 'Đồ uống';
        $category->save();

        $category = new Category();
        $category->name = 'Đồ ăn vặt';
        $category->save();

        $category = new Category();
        $category->name = 'Bánh kẹo';
        $category->save();
    }
}
