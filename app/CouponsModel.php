<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsModel extends Model
{
    protected $table = 'coupons';

    public function values()
    {
        return $this->hasMany('CouponsValuesModel');
    }
}
