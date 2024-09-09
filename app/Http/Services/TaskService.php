<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class TaskService {
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    public function getAllTAsks($priority,$status){
        try {
            //إعادة جميع المهام و استخدام سكوب فلتر في حالة أراد الأدمن أو المدير الفلترة حسب الحالة للمهة أو درجة أهميتها
            return Task::filter($priority,$status)->get();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->errorResponse('Something went wrong with fetche tasks', 400);
        }
    }
    //========================================================================================================================
    public function createTask($data) {
        try {
            //dd(Auth::user());
            //التحقق من وجود الموظف
            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user = User::findOrFail($data['user_id']);

                //التحقق من عدم إستاد المهمة لمستخدم ليس موظفاً
                if ($user->role != 'employee') {
                    throw new \Exception('You cannot assign a task to a manager or admin , only to employees');
                }
            }
    
            $task = Task::create($data);
            return $task;
    
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with creating the task', 400);
        }
    }
    
    //========================================================================================================================
    public function updateTask($data,Task $task){
        try {
            //التحقق في ما إذا كان الذي يطلب الاستعلام هو مدير فسوف يتم التحقق أولا إذا كان قد أنشئ من قبل أو لا
            if(Auth::id() != $task->created_by && Auth::user()->role == 'manager'){
                throw new \Exception('You cannot update this task because its dos not belongs to you');
            }
            $task->update($data);
            return $task;

        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with updating task', 400);
        }
    }
    //========================================================================================================================
    public function deleteTask(Task $task)
    {
        try {   
            //التحقق في ما إذا كان الذي يطلب الاستعلام هو مدير فسوف يتم التحقق أولا إذا كان قد أنشئ من قبل أو لا
            if(Auth::id() != $task->created_by && Auth::user()->role == 'manager'){
                throw new \Exception('You cannot delete this task because its dos not belongs to you');
            }
            $task->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting task', 400);}
    }
    //========================================================================================================================

    public function restoreTask($task_id)
    {
        try {
            $task = Task::withTrashed()->findOrFail($task_id);
            //التحقق في ما إذا كان الذي يطلب الاستعلام هو مدير فسوف يتم التحقق أولا إذا كان قد أنشئ من قبل أو لا
            if(Auth::id() != $task->created_by && Auth::user()->role == 'manager'){
                throw new \Exception('You cannot delete this task because its dos not belongs to you');
            }

            $task = Task::withTrashed()->findOrFail($task_id);
            return $task->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with restore task', 400);
        }
    }
    //========================================================================================================================

    public function forceDeleteTask($task_id)
    {   
        try {
            $task = Task::findOrFail($task_id);
            //التحقق في ما إذا كان الذي يطلب الاستعلام هو مدير فسوف يتم التحقق أولا إذا كان قد أنشئ من قبل أو لا
            if(Auth::id() != $task->created_by && Auth::user()->role == 'manager'){
                throw new \Exception('You cannot delete this task because its dos not belongs to you');
            }

            $task = Task::findOrFail($task_id);
            return $task->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with deleting task', 400);}
    }
    //========================================================================================================================
    public function AssignTask($data, $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);
            
            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $user = User::findOrFail($data['user_id']);

                //التحقق من عدم إستاد المهمة لمستخدم ليس موظفاً
                if ($user->role != 'employee') {
                    throw new \Exception('You cannot assign a task to a manager, only to employees');
                }
            }
    
            $task->update($data);
            return $task;
    
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with assign task', 400);}
    }
    //========================================================================================================================
    public function updatedStatus($data , $task_id)
    {   
        try {
            $task = Task::findOrFail($task_id);
            if($task->user_id != Auth::id()){
                throw new \Exception('You cannot update the status of this task because its not belongs to you');
            }
                $task->update($data);
            return $task;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->errorResponse($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->errorResponse('Something went wrong with updating status', 400);}
    }
    //========================================================================================================================



}
