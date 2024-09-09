<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $permissions = Permission::whereBetween('id', [1,17])->pluck('id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole($role->id);

        $rolemanager = Role::create(['name' => 'manager']);
        $permissionsmanager = Permission::whereBetween('id', [10,17])->pluck('id')->all();
        $rolemanager->syncPermissions($permissionsmanager);


        $roleemployee = Role::create(['name' => 'employee']);
        $permissionsemployee = Permission::where('id', 18)->pluck('id')->all();
        $roleemployee->syncPermissions($permissionsemployee);

    }
}
