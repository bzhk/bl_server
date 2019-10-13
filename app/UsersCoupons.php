<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersCoupons extends Model
{
    protected $table = 'users_coupons';
    
    public function coupons(){
        return $this->belongsTo('App\CouponsModel','coupon_id','id')->select('id','unique_value');
    }
    
}
