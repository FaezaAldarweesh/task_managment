<?php

namespace App\Http\Controllers;
use App\Http\Requests\loginRequest;
use App\Http\Services\Authservices;
use App\Http\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $authservices;    
    /**
     * construct to inject auth services
     * @param Authservices $authservices
     */
    public function __construct(Authservices $authservices)
    {
        $this->authservices = $authservices;
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    //===========================================================================================================================
    /**
     * function to login users
     * @param loginRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request)
    {        
        $token = $this->authservices->login($request->validated());
        return $this->AuthResponse(null,$token,"login has been successfully",200);
    }
//===========================================================================================================================
    /**
     * function to logout users
     * @return /Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->authservices->logout();
        return $this->AuthResponse(null,null,"Successfully logged out",200);
    }

}
