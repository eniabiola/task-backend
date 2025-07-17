<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'task.create',
            'task.view.any',
            'task.view.own',
            'task.update.any',
            'task.update.own',
            'task.delete.any',
            'task.delete.own',
            'task.change_status',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        $admin = Role::query()->firstOrCreate(['name' => 'admin']);
        $manager = Role::query()->firstOrCreate(['name' => 'manager']);
        $user = Role::query()->firstOrCreate(['name' => 'user']);
        $viewer = Role::query()->firstOrCreate(['name' => 'viewer']);

        $admin->syncPermissions($permissions);
        $manager->syncPermissions([
            'task.create',
            'task.view.any',
            'task.view.own',
            'task.update.own',
            'task.delete.own',
            'task.change_status',
        ]);
        $user->syncPermissions([
            'task.create',
            'task.view.own',
            'task.update.own',
            'task.delete.own',
            'task.change_status',
        ]);
        $viewer->syncPermissions(['task.view.own']);
    }
}
