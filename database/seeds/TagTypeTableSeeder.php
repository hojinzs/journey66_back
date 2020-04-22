<?php

use Illuminate\Database\Seeder;

class TagTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\TagType::class,10)
            ->create();
    }
}
