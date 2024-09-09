## Task Management System

A web-based application designed for managing tasks and assigning them to employees. 
This system allows administrators and managers to create, update, and delete tasks while ensuring that only employees can be assigned tasks. 
The application is built using Laravel and includes role-based access control.

## Features
- Task Creation: Create tasks with attributes like title, description, priority, due date, and status.
- Task Assignment: Assign tasks to employees only. Managers and admins cannot be assigned tasks.
- Task Filtering: Filter tasks based on priority and status.
- Role-based Access: Only employees can be assigned tasks, and only the creator or a manager can edit or delete tasks.
- Soft Deletes: Tasks are not permanently deleted but can be restored if needed.

## Installation
- git clone https://github.com/FaezaAldarweesh/task_managment.git
- composer install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- php artisan serve

## Usage
- Creating Tasks: Tasks can be created by admins and assigned to employees.
- Filtering Tasks: Tasks can be filtered by priority (High, Medium, Low) or status (Assigned, Received, Done).
- Editing Tasks: Only the task creator or a manager can edit or update tasks.
- Task Deletion: Tasks are soft deleted and can be restored later if needed.

## API Endpoints
- apiResource user
- Post/create_manager
- Get/update_password/{user_id}
- Get/restore_user/{user_id}
- Delete/forceDelete_user/{user_id}
- apiResource task
- Get/restore_task/{task_id}
- Delete/forceDelete_task/{task_id}
- Put/Assign_task/{task_id}
- Put/updated_status/{task_id}

  ## postman
  - documentation link : https://documenter.getpostman.com/view/34467473/2sAXjSyoEz
