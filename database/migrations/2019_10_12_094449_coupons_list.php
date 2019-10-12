<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponsList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('partner_id');
            $table->string('name');
            $table->string('img_url');
            $table->string('offert_url');
            $table->text('description');     
            $table->bigInteger('points_cost');
            $table->double('points_cost_reduction',2,1)->default(0.0);
            $table->date('expiry_date');
            $table->timestamps();           

            $table->foreign('partner_id')->references('id')->on('partners');
        });

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
