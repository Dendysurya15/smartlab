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
        // Existing roles and permissions
        $role_superuser = Role::updateOrCreate(['name' => 'superuser']);
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
        Permission::updateOrCreate(['name' => 'verify_kupa']);

        Role::updateOrCreate(['name' => 'Staff'], ['alur_approved' => 2])->givePermissionTo([
            'create_new_user',
            'view_role_management',
            'view_history_kupa',
            'view_dashboard_smartlab',
            'view_halaman_parameter_analisis',
            'update_status_pengerjaan_kupa',
            'export_kupa',
            'edit_kupa',
            'hapus_kupa',
            'input_kupa',
            'verify_kupa',
            'export_form_monitoring_kupa'
        ]);
        Role::updateOrCreate(['name' => 'Admin'], ['alur_approved' => 1])->givePermissionTo([
            'create_new_user',
            'view_role_management',
            'view_history_kupa',
            'view_dashboard_smartlab',
            'view_halaman_parameter_analisis',
            'update_status_pengerjaan_kupa',
            'export_kupa',
            'edit_kupa',
            'hapus_kupa',
            'input_kupa',
            'verify_kupa',
            'export_form_monitoring_kupa'
        ]);
        Role::updateOrCreate(['name' => 'Head Of Lab SRS'], ['alur_approved' => 4])->givePermissionTo([
            'view_history_kupa',
            'view_dashboard_smartlab',
            'view_halaman_parameter_analisis',
            'verify_kupa',
            'export_kupa'
        ]);
        Role::updateOrCreate(['name' => 'Asmen Lab Analitik'], ['alur_approved' => 3])->givePermissionTo([
            'view_history_kupa',
            'view_dashboard_smartlab',
            'view_halaman_parameter_analisis',
            'export_kupa'
        ]);

        // New permissions
        Permission::updateOrCreate(['name' => 'check_invoice']);
        Permission::updateOrCreate(['name' => 'update_invoice']);
        Permission::updateOrCreate(['name' => 'send_invoice']);
        Permission::updateOrCreate(['name' => 'delete_invoice']);

        // New role with new permissions
        $role_markom = Role::updateOrCreate(['name' => 'marcom'])->givePermissionTo([
            'check_invoice',
            'update_invoice',
            'send_invoice',
            'export_form_monitoring_kupa',
            'delete_invoice',
            'input_kupa',
            'view_history_kupa'
        ]);

        // Assign all permissions to the superuser role
        $allPermissions = Permission::pluck('id')->toArray();
        $role_superuser->syncPermissions($allPermissions);
    }
}
