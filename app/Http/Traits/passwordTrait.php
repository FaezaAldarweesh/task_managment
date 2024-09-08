<?php 

namespace App\Http\Traits;


trait passwordTrait 
{
    public function RandomPassword(){
        $array_pass = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $pass_value = "";
            $length = strlen($array_pass);
            
            for ($i = 0; $i < 8; $i++) {
                $pass_value .= $array_pass[rand(0, $length - 1)];
            }

        return $pass_value;
    }
}


    
