<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Traits\passwordTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use Spatie\Permission\Models\Role;


class UserService {
    //ApiResponseTrait trait لقولبة رسائل الاستجابة
    //passwordTrait trait من أجل توليد كلمة المرور بشكل عشزائي
    use ApiResponseTrait,passwordTrait;
    public function getAllUsers($role){
        try {
            //إعادة جميع المستخدمين و استخدام سكوب فلتر في حالة أراد الأدمن الفلترة حسب الأدوار
            $user = User::filter($role)->get();
            return $user;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with fetche users', 400);
        }
    }
    //========================================================================================================================
    public function createEmployee($data) {
        try {
            //لان كلمة المرور هي عنصر محمي guarded
            //فيجب حمايته من هجمات mass assignment
            //قمت بجعل كلمة المرور تولد عشوائيا دون تدخل الأدمن
            $pass_value = $this->RandomPassword();
    
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($pass_value);  
            //تحديد الدور لأن الدور ايضا خاصية محمية , لذلك في حال أراد الأدمن إنشاء موظف يقوم بإستدعاء هذا ال api
            $user->role = 'employee';
            $user->save();

            $userRole = Role::firstOrCreate(['name' => 'employee']);
            $user->assignRole($userRole);

            //إعادة معلومات الموظف و كلمة المرور التي ولدها الموقع بشكل عشوائي قبل عملية تشفيرها
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
            //تحديد الدور لأن الدور ايضا خاصية محمية , لذلك في حال أراد الأدمن إنشاء مدير يقوم بإستدعاء هذا ال api
            $user->role = 'manager';
            $user->save();

            $userRole = Role::firstOrCreate(['name' => 'manager']);
            $user->assignRole($userRole);

            return ['user' => $user, 'password' => $pass_value];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with creating manager', 400);
        }
    }
    //========================================================================================================================
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
    //لأن كلمة المرور عنصر محمي لذلك في حال أراد الأدمن التعديل على كلمة المرور فإنه سيقوم باستدعاء التابع التالي الذي يولدها أيضا بشكل عشوائي
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
                throw new \Exception('You cannot soft delete admin account');
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
            //البحث عن المستخدم المراد إعادته من ضمن المستخدمين المحذوفين مؤقتاً
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
