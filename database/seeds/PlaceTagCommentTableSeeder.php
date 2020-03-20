<?php

use Illuminate\Database\Seeder;
use App\PlaceTagComment;

class PlaceTagCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(PlaceTagComment::class, 50)
            ->make()
            ->each(function ($tagComment) {
                $tagComment->create();
            });
    }
}