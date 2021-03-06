<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('last_comment_id')->nullable();
            $table->integer('tagging_counts')->nullable();
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_tag');
    }
}
