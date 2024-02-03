<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using updateOrCreate method to ensure roles are unique
        // $role_superuser = Role::updateOrCreate(['name' => 'superuser']);
        // $role_admin = Role::updateOrCreate(['name' => 'admin']);
        // $role_user = Role::updateOrCreate(['name' => 'user']);

        // // Define permissions (if needed)
        // // Permission
        // $permission1 = Permission::updateOrCreate(['name' => 'view_rolemanagement']);
        // $permission2 = Permission::updateOrCreate(['name' => 'edit_data']);
        // $permission3 = Permission::updateOrCreate(['name' => 'view_dashboard']);
        // $permission4 = Permission::updateOrCreate(['name' => 'download']);
        // // Add more permissions here...

        // // Assign all permissions to the superuser role
        // $allPermissions = Permission::pluck('id')->toArray();
        // $role_superuser->syncPermissions($allPermissions);

        // // Assign specific permissions to the admin role
        // $role_admin->givePermissionTo([$permission1, $permission2]);

        // // User
        // // Assign roles to users (assuming you have already defined users)
        // // $superuser = User::find(2); // Assuming user with ID 2 is the superuser
        // $user = User::find(1); // Assuming user with ID 1 is the admin
        // $superuser = User::find(2); // Assuming user with ID 1 is the admin

        // $user->assignRole('admin');
        // $superuser->assignRole('superuser');
        // // $user->assignRole('user');
    }
}
