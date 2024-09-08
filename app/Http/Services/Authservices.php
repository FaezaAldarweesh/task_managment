<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;


class Authservices {
    use ApiResponseTrait;
    public function login($credentials){
        // This method authenticates a user with their email and password. 
        //When a user is successfully authenticated, the Auth facade attempt() method returns the JWT token. 
        //The generated token is retrieved and returned as JSON with the user object

        try {
            $token = Auth::attempt($credentials);
            if (!$token) {
                return response()->json(['status' => 'error','message' => 'Unauthorized',], 401);
            }
            return $token;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with login', 400);
        }

    }
    //========================================================================================================================
    public function logout(){
        try {
            Auth::logout();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with logout', 400);
        }
    }
    //========================================================================================================================
}
