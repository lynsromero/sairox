<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_posts', 'create_posts', 'edit_posts', 'delete_posts', 'publish_posts',
            'view_media', 'upload_media', 'delete_media',
            'manage_settings',
            'manage_users',
            'manage_categories',
            'manage_menus',
            'manage_widgets',
            'manage_comments',
            'manage_plugins',
            'manage_themes',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            'Super Admin' => $permissions,
            'Admin' => [
                'view_posts', 'create_posts', 'edit_posts', 'delete_posts', 'publish_posts',
                'view_media', 'upload_media', 'delete_media',
                'manage_settings',
                'manage_users',
                'manage_categories',
                'manage_menus',
                'manage_widgets',
                'manage_comments',
                'manage_plugins',
                'manage_themes',
            ],
            'Editor' => [
                'view_posts', 'create_posts', 'edit_posts', 'delete_posts', 'publish_posts',
                'view_media', 'upload_media',
                'manage_categories',
                'manage_comments',
            ],
            'Author' => [
                'view_posts', 'create_posts', 'edit_posts',
                'view_media', 'upload_media',
            ],
            'Contributor' => [
                'view_posts', 'create_posts',
                'view_media',
            ],
            'Subscriber' => [
                'view_posts',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
