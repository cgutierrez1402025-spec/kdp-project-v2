<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Platform;
use App\Models\Marketplace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'Author', 'Editor', 'Accountant'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['name' => $roleName, 'guard_name' => 'web']
            );
        }

        $permissions = [
            'view_works',
            'create_works',
            'edit_works',
            'delete_works',
            'view_royalties',
            'manage_promotions',
        ];
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web'],
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    DB::table('role_has_permissions')->updateOrInsert([
                        'role_id' => $adminRole->id,
                        'permission_id' => $permission->id,
                    ]);
                }
            }
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@kdpmanager.local'],
            ['name' => 'Administrador', 'password' => Hash::make('password')]
        );

        if ($adminRole) {
            DB::table('model_has_roles')->updateOrInsert(
                [
                    'role_id' => $adminRole->id,
                    'model_type' => User::class,
                    'model_id' => $admin->id,
                ]
            );
        }

        $author = User::firstOrCreate(
            ['email' => 'author@example.com'],
            ['name' => 'Author Example', 'password' => Hash::make('password')]
        );

        $authorRole = Role::where('name', 'Author')->first();
        if ($authorRole) {
            DB::table('model_has_roles')->updateOrInsert(
                [
                    'role_id' => $authorRole->id,
                    'model_type' => User::class,
                    'model_id' => $author->id,
                ]
            );
        }

        $kdp = Platform::firstOrCreate(
            ['name' => 'Amazon KDP'],
            ['description' => 'Kindle Direct Publishing']
        );

        Marketplace::firstOrCreate(
            ['platform_id' => $kdp->id, 'code' => 'amazon.com'],
            ['name' => 'Amazon US', 'currency' => 'USD']
        );

        Marketplace::firstOrCreate(
            ['platform_id' => $kdp->id, 'code' => 'amazon.es'],
            ['name' => 'Amazon España', 'currency' => 'EUR']
        );

        Platform::firstOrCreate(
            ['name' => 'SmashWords'],
            ['description' => 'Distribución digital multiformato']
        );

        Platform::firstOrCreate(
            ['name' => 'Draft2Digital'],
            ['description' => 'Distribución y publicación digital']
        );
    }
}