<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            'All Users',
            'View User',
            'Add User',
            'Edit User',
            'Delete User',
            'create manager',
            'update password',
            'restore user',
            'forceDelete user',
            
            'All tasks',
            'View task',
            'Add task',
            'Edit task',
            'Delete task',
            'restore task',
            'forceDelete task',
            'Assign_task',
            
            'updated status',
        ];

        foreach ($permissions as $permission) {

            Permission::create(['name' => $permission]);
        }
    }
}
