<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer_create = new User();
        $developer_create->name = 'cvhoang';
        $developer_create->email = 'test@gmail.com';
        $developer_create->password = bcrypt('12345678');
        $developer_create->image = '1.png';
        $developer_create->phone = '012345678';
        $developer_create->role = 2;
        $developer_create->role_admin = 255;
        $developer_create->save();

        $developer_delete = new User();
        $developer_delete->name = 'cvhoang1';
        $developer_delete->email = 'dev_delete@test.com';
        $developer_delete->password = bcrypt('12345678');
        $developer_delete->image = '1.png';
        $developer_delete->phone = '012345678';
        $developer_delete->role = 1;
        $developer_delete->save();

        $developer_full = new User();
        $developer_full->name = 'cvhoang2';
        $developer_full->email = 'dev_full@test.com';
        $developer_full->password = bcrypt('12345678');
        $developer_full->image = '1.png';
        $developer_full->phone = '012345678';
        $developer_full->role = 1;
        $developer_full->save();

        $manager = new User();
        $manager->name = 'cvhoang3';
        $manager->email = 'manager@test.com';
        $manager->password = bcrypt('12345678');
        $manager->image = '1.png';
        $manager->phone = '012345678';
        $manager->role = 1;
        $manager->save();

        $manager1 = new User();
        $manager1->name = 'cvhoang4';
        $manager1->email = 'manager1@test.com';
        $manager1->password = bcrypt('12345678');
        $manager1->image = '1.png';
        $manager1->phone = '012345678';
        $manager1->role = 1;
        $manager1->save();
    }
}
