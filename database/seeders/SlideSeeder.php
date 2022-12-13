<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slide;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slide = new Slide();
        $slide->image = '1.jpg';
        $slide->status = 0;
        $slide->save();

        $slide = new Slide();
        $slide->image = '2.jpg';
        $slide->status = 0;
        $slide->save();

        $slide = new Slide();
        $slide->image = '3.jpg';
        $slide->status = 1;
        $slide->save();

        $slide = new Slide();
        $slide->image = '4.jpg';
        $slide->status = 1;
        $slide->save();

        $slide = new Slide();
        $slide->image = '5.png';
        $slide->status = 1;
        $slide->save();

        $slide = new Slide();
        $slide->image = '6.jpg';
        $slide->status = 1;
        $slide->save();
    }
}
