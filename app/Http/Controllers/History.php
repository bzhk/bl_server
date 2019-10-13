<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class History extends Controller
{
    public function getList(Request $req)
    {
        try {
            $token = $req->header('AuthUser');
            $tags = $this->getSQLList($token);
            return response($tags,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }
    }

    private function getSQLList($token)
    {
        try {
            $tags = User::select('name','id')->where('token',$token)->with(['history'=>function($q){
                $q
                ->select('user_id','history_event','reduce_points','after_reduce_points','created_at')
                ->orderBy('created_at','desc');
            }])->get();
            return $tags;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }
    }
}
