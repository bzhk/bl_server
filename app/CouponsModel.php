<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsModel extends Model
{
    protected $table = 'coupons_values';

    public function values()
    {
        return $this->hasMany('CouponsValuesModel');
    }
}
