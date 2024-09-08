<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class TaskService {
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    public function getAllTAsks(){
        try {
            return Task::all();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with fetche tasks', 400);
        }
    }
    //========================================================================================================================
    public function createTask($data) {
        try {
            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user = User::findOrFail($data['user_id']);
                
                if ($user->role != 'employee') {
                    throw new \Exception('You cannot assign a task to a manager, only to employees');
                }
            }
            
            return Task::create($data);
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with creating the task', 400);
        }
    }   
    //========================================================================================================================
    public function updateTask($data,Task $task){
        try {
            $task->update($data);
            return $task;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with updating task', 400);
        }
    }
    //========================================================================================================================
    public function deleteTask(Task $task)
    {
        try {   
            $task->delete();
            return true;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting task', 400);}
    }
    //========================================================================================================================

    public function restoreTask($task_id)
    {
        try {
            $task = Task::withTrashed()->findOrFail($task_id);
            return $task->restore();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with restore task', 400);
        }
    }
    //========================================================================================================================

    public function forceDeleteTask($task_id)
    {   
        try {
            $task = Task::findOrFail($task_id);
            return $task->forceDelete();
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting task', 400);}
    }


}
