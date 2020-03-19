<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaceRecommendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_recommends', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->unsignedBigInteger('place_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('hidden')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('place_id')->references('id')->on('places');
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
        Schema::dropIfExists('place_recommends');
    }
}
