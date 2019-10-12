<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponsModel;
class Coupons extends Controller
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

    private function getSQLList()
    {
        try {
            return CouponsModel::with('values')->get();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 100);
            
        }
        
    }
}
