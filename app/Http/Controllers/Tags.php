<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TagsModel;

class Tags extends Controller
{
    public function getList(Request $req)
    {
        try {
            $tags = $this->getSQLList();
            return response($tags,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }
    }

    private function getSQLList()
    {
        try {
            $tags = TagsModel::get();
            return $tags;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
    }
}
