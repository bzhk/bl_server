<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsValuesModel extends Model
{
    protected $table = 'coupons_values';
    //

    public function value()
    {
        return $this->belongsTo('App\CouponsModel','coupon_id','id');
    }
}
