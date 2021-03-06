<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class Users extends Controller
{
    public function getContent(Request $req)
    {
        try {
           
            $email = $req->email;
            $password = $req->password;
            $content = $this->getSQLContent($password, $email);
            return response($content,200);
        } catch (\Exception $e) {
            return response($e->getMessage(),500);
        }
    }

    private function getSQLContent($password, $email)
    {
        try {
            if(!$password || !$email) throw new \Exception("Błędne dane.", 100);
            $content = User::
            where('email',$email)
            ->select('email','points','name','token','password')
            ->first();
            if(!$content)throw new \Exception("Brak danych", 100);
            $check = \Hash::check($password,$content['password']);
            if(!$check) throw new \Exception("Brak danych", 100);
            unset($content['password']);
            return $content;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
        }
    }

    public function getContentByToken(Request $req)
    {
        try {
            $token = $req->header('AuthUser');
            $item = $this->getSQLContentByToken($token);
            return response($item, 200);
        } catch (\Exception $e) {
            $resp = $this->parseError($e);
            return response($resp,500);
        }
    }

    private function getSQLContentByToken($token)
    {
        try {
            $user = User::where('token',$token)->select('id','token','name','points','email','created_at')->first();        
            return $user;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
        }
    }
}
