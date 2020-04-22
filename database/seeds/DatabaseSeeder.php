<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PlaceTypeTableSeeder::class);
        $this->call(TagTypeTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(PlacesTableSeeder::class);
        $this->call(PlaceRecommendsSeeder::class);
//         $this->call(PlaceTagCommentTableSeeder::class); 미완성
    }
}
