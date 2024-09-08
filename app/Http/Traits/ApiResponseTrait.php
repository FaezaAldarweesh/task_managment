<?php 

namespace App\Http\Traits;


// trait  من أجل قولبة رسائل الإستجابة المعادة 
trait ApiResponseTrait 
{
    public function AuthResponse($data,$token,$message,$status){
        $array = [
            'data'=>$data,
            'message'=>$message,
            'access_token'=>$token,
            'token_type'=>'bearer',
        ];

        return response()->json($array,$status);
    }

    //========================================================================================================================
    
    public function errorResponse($message,$status){
        return response()->json($message,$status);
    }
    //========================================================================================================================
    public function SuccessResponse($data,$message,$status){
        $array = [
            'data'          => $data,
            'message'       => $message,
        ];
        return response()->json($array, $status);
    }
}


    
