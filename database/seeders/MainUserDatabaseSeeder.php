<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MainUserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::create(['name' => 'user']);
        $ManagerRole = Role::create(['name' => 'manager']);

        $managerPermissions = [
            'view requests',
            'create requests',
            'close requests',
            'add messages',
            'view messages',
            'create categories',
            'update categories',
            'view categories',
        ];

        $userPermissions = [
            'view requests',
            'create requests',
            'add messages',
            'view messages',
        ];

        $allPermissions = array_unique(array_merge($managerPermissions, $userPermissions));

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $userRole->syncPermissions($userPermissions);
        $ManagerRole->syncPermissions($managerPermissions);

        $manager = User::factory()->create([
            'first_name' => 'Manager',
            'last_name' => 'Admin',
            'email' => 'manager@admin.com',
            'gender' => 1,
        ]);

        $manager->assignRole($ManagerRole);

    }
}
