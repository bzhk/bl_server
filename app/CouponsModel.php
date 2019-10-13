<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsModel extends Model
{
    protected $table = 'coupons';

    public function user()
    {
        return $this->hasMany('App\UsersCoupons','coupon_id','id');
    }

    public function values()
    {
        return $this->hasMany('App\CouponsValuesModel','coupon_id','id');
    }

    public function partner()
    {
        return $this->belongsTo('App\PartnersModel','partner_id','id');
    }
}
