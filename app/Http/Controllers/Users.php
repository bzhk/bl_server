<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class Users extends Controller
{
    public function getContent(request $req)
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
            return response($content,200);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 100);
        }
    }
}
