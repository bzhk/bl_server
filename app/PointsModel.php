<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';

    public function tagsList()
    {
        return $this->hasMany('App\PointsTagsModel','point_id','id');
    }
}
