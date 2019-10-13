<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponsModel;
use App\User;
use App\UsersCoupons;

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

    public function getItem(Request $req, $id){
        try {
            $user_auth = $req->header('AuthUser');
            $item = $this->getSQLItem( $id);
            $value = $this->getCouponValue($id,$user_auth);
            
            return response(['item' => $item, 'value'=>$value], 200);
        } catch (\Exception $e) {
            $resp = $this->parseError($e);
            return response($resp,500);
        }
    }
    private function getSQLList()
    {
        try {
            return CouponsModel::with(['partner'])->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }  
    }

    private function getSQLItem($id)
    {
        try {
            return CouponsModel::where("id",$id)->with(['partner'])->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
            
        }  
    }

    public function getCouponValue($id,$token)
    {
        try {
            
            if(!$token) return [];
            $user = User::where('token',$token)->first();
            
            if(!$user)return [];
            $coupons_value = $this->getPersonalCouponValue($user['id'],$id);
            return $coupons_value;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    public function getPersonalCouponValue($id,$coupon_id)
    {
        try {
            $values = UsersCoupons::select('user_id','coupon_id')
            ->where('user_id',$id)
            ->where('coupon_id',$coupon_id)
            ->with(['coupons'])->first();
            if(!$values || !$values->coupons) return [];
           
            $user_id = $values->user_id;
            $coupon_id = $values->coupon_id;
            if($values->coupons->unique_value === 1){
                return CouponsModel::select('id','unique_value')->where('id',$coupon_id)->with(['values' => function($q) use($user_id){
                    $q->select('value','coupon_id')->where('owner_user_id',$user_id);
                }])->first();
            }else{
                return CouponsModel::select('id','unique_value')->with(['values' => function($q){
                    $q->select('value','coupon_id');
                }])->first();
            }
            return $values;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }
}
