<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth;

class AuthHelper{
    public function create_token($credentials){
        $logged_in = Auth::attempt($credentials);
        if($logged_in){
            //create token for logged in user
            $user_data = Auth::user();
            $user_abilities = "*";
            $token = request()->user()->createToken('api_token',[$user_abilities])->plainTextToken; //get token only for response
            return $token;
        }else{
            return false;
        }
    }
}