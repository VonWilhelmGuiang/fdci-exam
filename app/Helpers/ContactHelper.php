<?php
namespace App\Helpers;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactHelper{
    public function is_valid_contact($contact_data = [], $validations = []) {
        $validator = Validator::make($contact_data,$validations); 

        //returns errors if not valid
        if($validator->fails()){
            $failed = ($validator->failed());
            $message = $validator->messages()->all();
            $duplicate_email = array_key_exists('email',$failed)? array_key_exists('Unique',$failed['email'])? true : false : false;
            if($duplicate_email){
                return ['message' => $message, 'code' => 409];
            }else{
                return['message' => $message, 'code' => 400];
            }
        }else{
            return true;
        }
    }
}
