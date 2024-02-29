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
        $role_superuser = Role::updateOrCreate(['name' => 'superuser']);
        // $role_admin = Role::updateOrCreate(['name' => 'admin']);
        // $role_user = Role::updateOrCreate(['name' => 'user']);

        // Define permissions (if needed)
        // Permission
        // $permission1 = Permission::updateOrCreate(['name' => 'view_rolemanagement']);
        // $permission2 = Permission::updateOrCreate(['name' => 'edit_data']);
        // $permission3 = Permission::updateOrCreate(['name' => 'view_dashboard']);
        // $permission4 = Permission::updateOrCreate(['name' => 'download']);

        Permission::updateOrCreate(['name' => 'view_dashboard_smartlab']);
        Permission::updateOrCreate(['name' => 'view_history_kupa']);
        Permission::updateOrCreate(['name' => 'input_kupa']);
        Permission::updateOrCreate(['name' => 'hapus_kupa']);
        Permission::updateOrCreate(['name' => 'edit_kupa']);
        Permission::updateOrCreate(['name' => 'export_kupa']);
        Permission::updateOrCreate(['name' => 'export_form_monitoring_kupa']);
        Permission::updateOrCreate(['name' => 'view_role_management']);
        Permission::updateOrCreate(['name' => 'view_halaman_parameter_analisis']);
        Permission::updateOrCreate(['name' => 'create_new_user']);
        Permission::updateOrCreate(['name' => 'update_status_pengerjaan_kupa']);

        Role::updateOrCreate(['name' => 'Staff'])->givePermissionTo(['create_new_user', 'view_role_management', 'view_history_kupa', 'view_dashboard_smartlab', 'view_halaman_parameter_analisis', 'update_status_pengerjaan_kupa', 'export_kupa', 'edit_kupa', 'hapus_kupa', 'input_kupa', 'export_form_monitoring_kupa']);
        Role::updateOrCreate(['name' => 'Admin'])->givePermissionTo(['create_new_user', 'view_role_management', 'view_history_kupa', 'view_dashboard_smartlab', 'view_halaman_parameter_analisis', 'update_status_pengerjaan_kupa', 'export_kupa', 'edit_kupa', 'hapus_kupa', 'input_kupa', 'export_form_monitoring_kupa']);
        Role::updateOrCreate(['name' => 'Head Of Lab SRS'])->givePermissionTo(['view_history_kupa', 'view_dashboard_smartlab', 'view_halaman_parameter_analisis', 'export_kupa']);
        Role::updateOrCreate(['name' => 'Asmen Lab Analitik'])->givePermissionTo(['view_history_kupa', 'view_dashboard_smartlab', 'view_halaman_parameter_analisis', 'export_kupa']);

        // Assign all permissions to the superuser role
        $allPermissions = Permission::pluck('id')->toArray();
        $role_superuser->syncPermissions($allPermissions);

        // // Assign specific permissions to the admin role
        // $role_admin->givePermissionTo([$permission1, $permission2]);

        // // User
        // // Assign roles to users (assuming you have already defined users)
        // // $superuser = User::find(2); // Assuming user with ID 2 is the superuser
        // $user = User::find(1); // Assuming user with ID 1 is the admin
        // $superuser = User::find(2); // Assuming user with ID 1 is the admin

        // $user->assignRole('admin');
        // $superuser->assignRole('superuser');
        // $user->assignRole('user');
    }
}
