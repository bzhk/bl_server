<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponsValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coupon_id');
            $table->string('value');
            $table->boolean('unique')->default(0);
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->timestamps();           

            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('owner_user_id')->references('id')->on('users');
        });

    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_values');
        //
    }
}
