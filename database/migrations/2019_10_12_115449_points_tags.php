<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PointsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('point_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();           

            $table->foreign('point_id')->references('id')->on('points');
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
        //
    }
}
