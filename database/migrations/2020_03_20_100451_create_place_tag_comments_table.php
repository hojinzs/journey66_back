<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceTagCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_tag_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('place_tag_id');
            $table->unsignedBigInteger('user_id');
            $table->text('content');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('place_tag_id')->references('id')->on('place_tag');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_tag_comments');
    }
}
