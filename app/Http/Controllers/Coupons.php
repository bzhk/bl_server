<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponsModel;
use App\User;
use App\UsersCoupons;
use App\UsersHistoryModel;

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

    public function unlockItem(Request $req, $id)
    {
        try{
            $user_token = $req->header('AuthUser');
        //    check if exist
            if(!$id || !$user_token)  throw new \Exception('Błędne dane wejściowe.',200); 
            $unlock = $this->isCanBeUnlocked($id, $user_token);
            if(!$unlock['canUnlock']) return response('Za mało punktów', 200);
            $unlocked = $this->unclockCoupon($unlock['id'], $id, $unlock['unique_coupon']);
            return [$unlocked];
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

    private function isCanBeUnlocked($coupon_id, $user_token)
    {
        try {
            
            
            $coupon = CouponsModel::select('points_cost','unique_value')->where("id",$coupon_id)->first();
            $user = User::select('points','id')->where('token',$user_token)->first();
            return [
                'canUnlock'=>$user->points >= $coupon->points_cost,
                'id'=>$user->id,
                'unique_coupon'=>$coupon->unique_value
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    private function unclockCoupon($user_id, $coupon_id, $unique)
    {
        try {
            
            if($unique){
                $code = $this->unlockSingleUniqueCoupon($user_id, $coupon_id);
            }else{
                $code = $this->unlockSingleUniversalCoupon($user_id, $coupon_id);
            }
            return $code;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }


    private function newRowInUsersCoupons($user_id, $coupon_id)
    {
        try {
            
            $row = new UsersCoupons;
            $row->coupon_id =  $coupon_id;
            $row->user_id =  $user_id;
            $row->save();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        }
    }
    private function unlockSingleUniversalCoupon($user_id, $coupon_id)
    {
        try {
            $this->newRowInUsersCoupons($user_id, $coupon_id);
            $result = $this->reductionUsersPoints($user_id, $coupon_id);
            $coupon_name = $result['name'];
            $event = "Wykupiono kupon $coupon_name";
            $e = $this->addNewEventHistory($user_id, $event, $result['reduce'],$result['after']);
            $coupon_value = $this->getCouponUniversalValue($coupon_id);
            return $coupon_value;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    private function unlockSingleUniqueCoupon($user_id, $coupon_id)
    {
        try {
            $this->newRowInUsersCoupons($user_id, $coupon_id);
            $coupon_value = $this->getCouponUniqueValue($coupon_id, $user_id);
            $result = $this->reductionUsersPoints($user_id, $coupon_id);
            $coupon_name = $result['name'];
            $event = "Wykupiono kupon $coupon_name";
            $this->addNewEventHistory($user_id, $event, $result['reduce'],$result['after']);
            return $coupon_value;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    private function reductionUsersPoints($user_id, $coupon_id)
    {
        try {
            $coupon = CouponsModel::select('points_cost','name')->where("id",$coupon_id)->first();
            $user = User::select('points','id')->where('id',$user_id)->first();
            $user->points = $user->points - $coupon->points_cost;
            $user->save();
            return ['after'=>$user->points,'reduce'=>$coupon->points_cost * -1,'name'=>$coupon->name];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    private function addNewEventHistory($user_id, $event_desc, $cost, $after_reduce)
    {
        
        try {
            $event = new UsersHistoryModel;
            $event->user_id=$user_id;
            $event->history_event=$event_desc;
            $event->reduce_points=$cost;
            $event->after_reduce_points=$after_reduce;
            $event->save();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);        
        }
    }
    
    private function getCouponUniversalValue($coupon_id)
    {
        try {
           $coupon = CouponsModel::where('id',$coupon_id)->with('values')->first();
           if(count($coupon->values) < 1) throw new \Exception('Brak kodu dla kuponu', 100);  
           return $coupon->values[0]->value;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }

    private function getCouponUniqueValue($coupon_id, $user_id)
    {
        try {
           $coupon = CouponsModel::where('id',$coupon_id)->with(['values' => function($q){
               $q->whereNull('owner_user_id');
           }])->first();
          
           if(count($coupon->values) < 1) throw new \Exception('Kody dla kupony skończyły się.', 100);  
           $coupon->values[0]->owner_user_id = $user_id;
           $coupon->values[0]->save();
           return $coupon->values[0]->value;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);     
        } 
    }
}
