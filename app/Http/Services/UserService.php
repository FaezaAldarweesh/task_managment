<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Traits\passwordTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class UserService {
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait,passwordTrait;
    public function getAllUsers(Request $request){
        try {
            $user = User::filter($request)->get();
            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with fetche users', 400);
        }
    }
    //========================================================================================================================
    public function createEmployee($data) {
        try {
            
            $pass_value = $this->RandomPassword();
    
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($pass_value);  
            $user->role = 'employee';
            $user->save();

            return ['user' => $user, 'password' => $pass_value];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with creating employee', 400);
        }
    }
          
    //========================================================================================================================
    public function createManager($data){
        try {

            $pass_value = $this->RandomPassword();

            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($pass_value);  
            $user->role = 'manager';
            $user->save();

            return ['user' => $user, 'password' => $pass_value];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with creating manager', 400);
        }
    }
    //========================================================================================================================
    // public function showUser($data){
    //     try {
    //         if(!$data){
    //             throw new \Exception('user not found');
    //         }          
    //     } catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
    //     } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with creating user', 400);}
    // }
    // //========================================================================================================================
    public function updateUser($data,User $user){
        try {
            $user->update($data);
            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with updating user', 400);
        }
    }
    //========================================================================================================================
    public function updatePassword($user_id){
        try {
            $user = User::findOrFail($user_id);

            if(!$user){
                throw new \Exception('user not found');
            }

            $pass_value = $this->RandomPassword();

            $user->password = $pass_value;
            $user->save();

            return ['user' => $user, 'password' => $pass_value];
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with updating password', 400);}
    }
    //========================================================================================================================

    public function deleteUser(User $user)
    {
        try {   
            //منع الأدمن من إزالة حسابه
            if ($user->role == 'Admin') {
                throw new \Exception('You cannot delete admin account');
            }
            $user->delete();
            return true;
            //catch error expception
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting user', 400);}
    }
    //========================================================================================================================

    public function restoreUser($user_id)
    {
        try {
            $user = User::withTrashed()->findOrFail($user_id);
            return $user->restore();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with restore user', 400);
        }
    }
    //========================================================================================================================

    public function forceDeleteUser($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
             //منع الأدمن من إزالة حسابه
             if ($user->role == 'Admin') {
                throw new \Exception('You cannot delete admin account');
            }
            return $user->forceDelete();
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting user', 400);}
    }


}
