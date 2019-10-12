<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PointsList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->text('desc');
            $table->string('phone');
            $table->string('email');
            $table->json('open')->default(`{"open":[
                {"Monday": "00:00-00:00"},
                {"Tuesday": "00:00-00:00"},
                {"Wednesday": "00:00-00:00"},
                {"Thursday": "00:00-00:00"},
                {"Friday": "00:00-00:00"},
                {"Saturday": "00:00-00:00"},
                {"Sunday": "00:00-00:00"},
            ]}`);
            $table->string('address');
            $table->double('lang',10,2);
            $table->double('lat',10,2);
            $table->timestamps();           

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
