<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using updateOrCreate method to ensure roles are unique
        $role_superuser = Role::updateOrCreate(['name' => 'superuser']);
        $role_admin = Role::updateOrCreate(['name' => 'admin']);
        $role_user = Role::updateOrCreate(['name' => 'user']);

        // Define permissions
        $permission1 = Permission::updateOrCreate(['name' => 'view_rolemanagement']);
        $permission2 = Permission::updateOrCreate(['name' => 'edit_data']);

        // Assign permissions to roles
        $role_superuser->givePermissionTo([$permission1, $permission2]);
        $role_admin->givePermissionTo($permission2);

        // Assign roles to users
        $superuser = User::find(2); // Assuming user with ID 2 is the superuser
        $admin = User::find(1); // Assuming user with ID 1 is the admin

        $superuser->assignRole('superuser');
        $admin->assignRole('admin');
    }
}
