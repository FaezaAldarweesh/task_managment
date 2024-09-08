<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Admin\StoreTaskRequest;
use App\Http\Requests\Admin\UpdateTsakRequest;


class TaskController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $taskservices;
    /**
     * construct to inject  Services 
     * @param taskService $taskservices
     */
    public function __construct(TaskService $taskservices)
    {
        $this->taskservices = $taskservices;
    }
    //===========================================================================================================================
    /**
     * method to view all 
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة UserResources استخدام 
     */
    public function index(Request $request)
    {  
        $users = $this->taskservices->getAllTAsks();
        return $this->SuccessResponse(TaskResources::collection($users), "All tasks fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new User
     * @param   $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $response = $this->taskservices->createTask($request->validated());
        return $this->SuccessResponse(new TaskResources($response), "Task created successfully.", 201);
    }
    //===========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param   $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        return $this->SuccessResponse(new TaskResources($task), "task viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param   $request
     * @param   $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateTsakRequest $request, Task $task)
    {
        $updated = $this->taskservices->updateTask($request->validated(), $task);
        return $this->SuccessResponse(new TaskResources($updated), "task updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete user alraedy exist
     * @param   $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $this->taskservices->deleteTask($task);
        return $this->SuccessResponse(null, "task deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete user alraedy exist
     * @param   $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($task_id)
    {
        $this->taskservices->restoreTask($task_id);
        return $this->SuccessResponse(null, "task restored successfully", 200);
    }
    //========================================================================================================================
        /**
     * method to force delete user alraedy exist
     * @param   $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($task_id)
    {
        $this->taskservices->forceDeleteTask($task_id);
        return $this->SuccessResponse(null, "task force deleted successfully", 200);
    }
        
    //========================================================================================================================

}
