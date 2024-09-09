<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // $password = $password;
        return [
            'task id' => $this->id,
            'employee name' => $this->user->name ?? 'NON',
            'employee email' => $this->user->email ?? 'NON',
            'task title' => $this->title,
            'task description' => $this->description,
            'task priority' => $this->priority,
            'task due_date' => $this->due_date,
            'task status' => $this->status,
        ];
    }
    
}
