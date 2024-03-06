<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();

        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12341234'),
        ]);

        // Assign 'admin' role to admin user
        $adminUser->assignRole($adminRole);

        // Create pembeli user
        $StaffUser = User::factory()->create([
            'name' => 'staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('12341234'),
        ]);

        // Assign 'pembeli' role to pembeli user
        $StaffUser->assignRole($staffRole);
    }
}
