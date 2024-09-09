<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $userservices;
    /**
     * construct to inject User Services and have middleware to make only user role access to this functions
     * @param UserService $userservices
     */
    public function __construct(UserService $userservices)
    {
        $this->middleware(['role:Admin', 'permission:All Users'])->only('index');
        $this->middleware(['role:Admin', 'permission:Add User'])->only('store');
        $this->middleware(['role:Admin', 'permission:create manager'])->only('create_manager');
        $this->middleware(['role:Admin', 'permission:View User'])->only('show');
        $this->middleware(['role:Admin', 'permission:Edit User'])->only('update');
        $this->middleware(['role:Admin', 'permission:update password'])->only('update_password');
        $this->middleware(['role:Admin', 'permission:Delete User'])->only('destroy');
        $this->middleware(['role:Admin', 'permission:restore user'])->only('restore');
        $this->middleware(['role:Admin', 'permission:forceDelete user'])->only('forceDelete');
        $this->userservices = $userservices;
    }
    //===========================================================================================================================
    /**
     * method to view all user
     * @param  Request $request
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة UserResources استخدام 
     */
    public function index(Request $request)
    {  
        $users = $this->userservices->getAllUsers($request->input('role'));
        return $this->SuccessResponse(UserResources::collection($users), "All Users fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new User
     * @param  StoreUserRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $response = $this->userservices->createEmployee($request->validated());
        return $this->SuccessResponse(['user Info' => new UserResources($response['user']),'password' => $response['password']], "Employee created successfully.", 201);
    }
    //===========================================================================================================================
        /**
     * method to store a new User
     * @param  StoreUserRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function create_manager(StoreUserRequest $request)
    {
        $response = $this->userservices->createManager($request->validated()); 
        return $this->SuccessResponse(['user Info' => new UserResources($response['user']),'password' => $response['password']], "Manager created successfully.", 201);
    }
    //===========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->SuccessResponse(new UserResources($user), "user viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param  UpdateUserRequest $request
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->userservices->updateUser($request->validated(), $user);
        return $this->SuccessResponse(new UserResources($updatedUser), "user updated successfully", 200);
    }
    //===========================================================================================================================
        /**
     * method to update password to user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update_password($user_id)
    {
        $updatedUser = $this->userservices->updatePassword($user_id);
        return $this->SuccessResponse(['user Info' => new UserResources($updatedUser['user']),'password' => $updatedUser['password']], "password updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete user alraedy exist
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userservices->deleteUser($user);
        return $this->SuccessResponse(null, "user soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($user_id)
    {
        $this->userservices->restoreUser($user_id);
        return $this->SuccessResponse(null, "user restored successfully", 200);
    }
    //========================================================================================================================
        /**
     * method to force delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($user_id)
    {
        $this->userservices->forceDeleteUser($user_id);
        return $this->SuccessResponse(null, "user force deleted successfully", 200);
    }
        
    //========================================================================================================================

}
