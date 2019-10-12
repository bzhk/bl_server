<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PointsModel;

class Points extends Controller
{
   
    public function getList(Request $req)
    {
        try {
            $list = $this->getSQLList();
            return response($list, 200);
        } catch (\Exception $e) {
            $resp = $this->parseError($e);
            return response($resp,500);
        }
       
    }

    public function getItem(Request $req, $id){
        try {
            $item = $this->getSQLItem($id);
            return response($item, 200);
        } catch (\Exception $e) {
            $resp = $this->parseError($e);
            return response($resp,500);
        }
    }

    private function getSQLList()
    {
        try {
            return PointsModel::with(['tagsList.item'])->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
        
    }

    private function getSQLItem($id)
    {
        try {
            return PointsModel::where("id",$id)->with(['tagsList.item'])->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
        
    }
}
