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

    public function getFilteredList(Request $req)
    {
    
        try {
            $tags = $req->query('tags');
            $position = $req->query('position');
            $list = $this->getSQLFilteredList($tags, $position);          
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

    private function getSQLFilteredList($tags, $position)
    {
        try {
            $parsed_tags = $this->parseTags($tags);
            $parsed_position = $this->parsePosition($position);
            return PointsModel::whereBetween('lang',[$parsed_position['lang_m5'],$parsed_position['lang_p5']])
            ->whereBetween('lat',[$parsed_position['lat_m5'],$parsed_position['lat_p5']])
            ->with(['tagsList' => function($q) use ($parsed_tags){
                $q->whereIn('tag_id',$parsed_tags);
            },'tagsList.item'])->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
    }

    private function parseTags($tags)
    {
        try{
            $parsed_tags = explode(",",$tags);
            return $parsed_tags;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
    }

    private function parsePosition($position)
    {
        try{
            $current_position = [52.2365787,21.01733];
            if($position){
                $current_position = explode(",",$position);
            }
           
            $current_position = [
                'lang_p5' => $current_position[1] + 1,
                'lang_m5' => $current_position[1] - 1,
                'lat_p5' => $current_position[0] + 1,
                'lat_m5' => $current_position[0] - 1,
            ];
            return $current_position;
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
