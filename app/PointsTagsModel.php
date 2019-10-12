<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointsTagsModel extends Model
{
    protected $table = 'points_tags';
    
    public function item()
    {
        return $this->belongsTo('App\TagsModel','tag_id','id');
    }
}
